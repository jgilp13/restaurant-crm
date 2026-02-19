#!/bin/sh
# Genera .env desde variables de entorno (para Render, Railway, etc.)
ENV_FILE="/var/www/html/.env"
if [ -n "$DB_HOST" ] || [ -n "$DB_NAME" ]; then
  cat > "$ENV_FILE" << EOF
DB_HOST=${DB_HOST:-localhost}
DB_NAME=${DB_NAME:-restaurant_crm}
DB_USER=${DB_USER:-root}
DB_PASS=${DB_PASS:-}
APP_ENV=${APP_ENV:-production}
APP_DEBUG=${APP_DEBUG:-false}
EOF
fi
exec apache2-foreground
