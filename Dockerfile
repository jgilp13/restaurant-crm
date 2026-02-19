FROM php:8.2-apache

# Extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite para .htaccess
RUN a2enmod rewrite

# Copiar código
COPY . /var/www/html

# Entrypoint para generar .env desde env vars
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# DocumentRoot = /public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Permitir .htaccess en todo el docroot
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Variables de entorno por defecto (Render las puede sobrescribir)
ENV APP_ENV=production
ENV APP_DEBUG=false

WORKDIR /var/www/html/public

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]