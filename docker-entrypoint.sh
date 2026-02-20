#!/bin/sh
# ========================================================================
# Entrypoint: Genera .env desde variables de entorno
# Soporta: Railway, Render, y desarrollo local
# ========================================================================
ENV_FILE="/var/www/html/.env"

# ========================================================================
# RAILWAY: Variables con prefijo MYSQLHOST_, etc.
# Ejemplo: MYSQLHOST_=caboose.proxy.rlwy.net
# ========================================================================
if [ -n "$MYSQLHOST_" ]; then
    DB_HOST="${MYSQLHOST_}"
    DB_PORT="${MYSQLPORT_:-3306}"
    DB_DATABASE="${MYSQLDATABASE_:-railway}"
    DB_USERNAME="${MYSQLUSER_:-root}"
    DB_PASSWORD="${MYSQLPASSWORD_:-}"
fi

# ========================================================================
# RENDER / ENV vars estándares: Sobrescriben Railway si existen
# ========================================================================
DB_HOST="${DB_HOST:-${MYSQL_HOST:-localhost}}"
DB_PORT="${DB_PORT:-${MYSQL_PORT:-3306}}"
DB_DATABASE="${DB_DATABASE:-${MYSQL_DATABASE:-restaurant_crm}}"
DB_USERNAME="${DB_USERNAME:-${MYSQL_USER:-root}}"
DB_PASSWORD="${DB_PASSWORD:-${MYSQL_PASSWORD:-}}"

# App config
APP_ENV="${APP_ENV:-production}"
APP_DEBUG="${APP_DEBUG:-false}"

# ========================================================================
# Generar archivo .env
# ========================================================================
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

# ========================================================================
# Logging para debugging
# ========================================================================
echo "========================================="
echo "✓ .env generado correctamente"
echo "========================================="
echo "  DB_HOST: $DB_HOST"
echo "  DB_PORT: $DB_PORT"
echo "  DB_NAME: $DB_DATABASE"
echo "  DB_USER: $DB_USERNAME"
echo "  APP_ENV: $APP_ENV"
echo "========================================="

# Ejecutar Apache
exec apache2-foreground
