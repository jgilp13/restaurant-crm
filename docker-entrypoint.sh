#!/bin/sh
# ========================================================================
# Entrypoint para Render: Genera .env desde variables de entorno
# ========================================================================
ENV_FILE="/var/www/html/.env"

# Variables requeridas para DB
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-restaurant_crm}"
DB_USERNAME="${DB_USERNAME:-root}"
DB_PASSWORD="${DB_PASSWORD:-}"
APP_ENV="${APP_ENV:-production}"
APP_DEBUG="${APP_DEBUG:-false}"

# Generar archivo .env
cat > "$ENV_FILE" << EOF
; Configuración de Base de Datos
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_NAME=$DB_DATABASE
DB_USER=$DB_USERNAME
DB_PASS=$DB_PASSWORD

; Configuración de Aplicación
APP_ENV=$APP_ENV
APP_DEBUG=$APP_DEBUG
ITEMS_PER_PAGE=10
EOF

# Log para debugging
echo "✓ .env generado correctamente"
echo "  DB_HOST: $DB_HOST"
echo "  DB_PORT: $DB_PORT"
echo "  DB_NAME: $DB_DATABASE"
echo "  APP_ENV: $APP_ENV"

# Ejecutar Apache
exec apache2-foreground
