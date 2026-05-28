#!/bin/bash
# deploy.sh - Script de despliegue para Perfil Global

echo "🚀 Iniciando despliegue..."

# Navegar al directorio del proyecto
cd /var/www/perfilglobal.masterteam.online

# Obtener los últimos cambios de la rama main
echo "📥 Descargando cambios de GitHub..."
git pull origin main

# Instalar/actualizar dependencias (sin dependencias de desarrollo y optimizando el autoloader)
echo "📦 Actualizando dependencias de Composer..."
sudo -u www-data composer install --no-dev --optimize-autoloader

# Asegurar permisos correctos (Nginx necesita ser el propietario)
echo "🔒 Ajustando permisos..."
sudo chown -R www-data:www-data /var/www/perfilglobal.masterteam.online
sudo find /var/www/perfilglobal.masterteam.online -type d -exec chmod 755 {} \;
sudo find /var/www/perfilglobal.masterteam.online -type f -exec chmod 644 {} \;

# Opcional: Reiniciar PHP-FPM para limpiar caché (si es necesario)
echo "🔄 Reiniciando PHP-FPM..."
sudo systemctl restart php8.3-fpm

echo "✅ Despliegue completado con éxito."