#!/bin/sh
set -e

echo "Ajustando permisos de almacenamiento..."
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Verificar que la variable DATABASE_URL esté definida
if [ -n "$DATABASE_URL" ]; then
    echo "DATABASE_URL detectada."
else
    echo "ADVERTENCIA: DATABASE_URL no está definida."
fi

# Forzar la optimización de caché en producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enlace simbólico para storage
php artisan storage:link --force || true

# Ejecutar migraciones y poblar la base de datos automáticamente
echo "Ejecutando migraciones y seeders de base de datos..."
php artisan migrate:fresh --seed --force

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Arrancar el servidor web Nginx en primer plano
echo "Iniciando Nginx..."
nginx -g 'daemon off;'