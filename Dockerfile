# Forzar reconstrucción limpia
ARG CACHE_BUST=20250320

# Etapa 1: Compilar frontend con Vite
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 2: Instalar dependencias PHP con Composer
FROM composer:2 AS composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Etapa 3: Servidor PHP de producción (PHP 8.3 requerido por WalkyDog)
FROM php:8.3-fpm-alpine

# Instalar dependencias del sistema y soporte para MySQL, PostgreSQL y GD (requerido para DomPDF)
RUN apk add --no-cache nginx postgresql-dev mysql-client libpng-dev libjpeg-turbo-dev freetype-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd

# Configurar límites de subida de archivos en PHP para el perfil
RUN echo "upload_max_filesize = 20M" > /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 20M" >> /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html
COPY . .

# Copiar frontend compilado (Vite)
COPY --from=frontend /app/public/build ./public/build

# Copiar vendor generado por Composer
COPY --from=composer /app/vendor ./vendor

# Corrección de permisos para el almacenamiento y la caché de Bootstrap
RUN mkdir -p storage/logs && \
    touch storage/logs/laravel.log && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración de Nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Script de entrada
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
CMD ["sh", "/usr/local/bin/entrypoint.sh"]