FROM php:8.2-apache

# Extensiones necesarias
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Habilitar módulos Apache necesarios
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod remoteip

# Copiar código
COPY . /var/www/html

# Entrypoint para generar .env desde env vars
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# ==============================================================
# CONFIGURACIÓN CRÍTICA PARA RENDER
# ==============================================================

# Crear archivo de configuración Apache mejorado
RUN cat > /etc/apache2/sites-available/app.conf << 'EOF'
# Aumentar límite de internal redirects para MVC/front controller
LimitInternalRecursion 50 50

# VirtualHost para la aplicación
<VirtualHost *:8080>
    ServerName localhost
    DocumentRoot /var/www/html/public
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    
    # Deshabilitar .htaccess processing (usar config aquí)
    <Directory /var/www/html/public>
        Options -MultiViews -Indexes +FollowSymLinks
        AllowOverride None
        Require all granted
        
        # HTTPS detection desde Render reverse proxy
        SetEnvIf X-Forwarded-Proto https HTTPS=on
        
        # Rewrite rules (copiar del .htaccess pero aquí)
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteBase /
            
            # No reescribir si es un archivo o directorio REAL
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            
            # Reescribir TODO a index.php (front controller)
            RewriteRule ^(.*)$ /index.php [QSA,L]
        </IfModule>
    </Directory>
    
    <Directory /var/www/html>
        AllowOverride None
        Require all denied
    </Directory>
    
    # Bloquear acceso a directorios sensibles
    <DirectoryMatch "^/var/www/html/(app|database|vendor|\.env)">
        Require all denied
    </DirectoryMatch>
</VirtualHost>
EOF

# Deshabilitar el sitio default que conflictúa
RUN a2dissite 000-default

# Habilitar nuestro sitio personalizado
RUN a2ensite app

# 3. Permisos de escritura para logs
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    find /var/www/html -type f -exec chmod 644 {} \;

# Variables de entorno por defecto  
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV DB_PORT=3306

# Puerto que escucha
EXPOSE 8080

# Cambiar puerto para Render (que usa 8080 por defecto)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

# Health check (simple)
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:8080/ || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]