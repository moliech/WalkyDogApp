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

# Ejecutar migraciones
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

# Opcional: Sembrar base de datos si no hay usuarios registrados
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
USER_COUNT=$(echo "$USER_COUNT" | tr -d '\r\n[:space:]')

if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "Base de datos vacía o primer inicio. Sembrando datos iniciales..."
    php artisan db:seed --force
else
    echo "La base de datos ya contiene datos ($USER_COUNT usuarios). Saltando db:seed."
fi

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Arrancar el servidor web Nginx en primer plano
echo "Iniciando Nginx..."
nginx -g 'daemon off;'