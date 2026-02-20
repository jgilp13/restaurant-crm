FROM php:8.2-apache

# Extensiones necesarias
RUN apt-get update && apt-get install -y --no-install-recommends \
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
RUN cat > /etc/apache2/conf-available/app.conf << 'EOF'
<Directory /var/www/html/public>
    Options -MultiViews -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
    
    # HTTPS detection desde Render reverse proxy
    SetEnvIf X-Forwarded-Proto https HTTPS=on
</Directory>
EOF

# 1. DocumentRoot apunta a /public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# 2. Habilitar la configuración personalizada
RUN a2enconf app

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