# 🐳 Testing Local con Docker

## ⚡ Quick Start
```bash
# 1. Compilar y ejecutar todo
docker-compose up --build

# 2. Ver logs en tiempo real (nueva terminal)
docker-compose logs -f web

# 3. Acceder a la app
# http://localhost:8080
# http://localhost:8080/login
```

## 📋 Pasos Detallados

### 1. Primera ejecución
```bash
# Construir imágenes y iniciar servicios
docker-compose up --build

# Salida esperada:
# ✓ "✓ .env generado correctamente"
# ✓ "restaurant-crm-db | ready for connections"
```

### 2. Probar conexión a DB
```bash
# Nueva terminal:
docker-compose exec db mysql -h localhost -u crm_user -psuper_secret_password restaurant_crm -e "SHOW TABLES;"

# Salida esperada: listado de tablas (users, restaurants, menus, etc.)
```

### 3. Inspeccionar Apache
```bash
docker-compose exec web apache2ctl -M
# Debe mostrar: mpm_prefork_module (shared), rewrite_module (shared), etc.
```

### 4. Ver logs de PHP/Apache en tiempo real
```bash
docker-compose logs -f web

# Buscar errores:
# - PHP Fatal Error
# - Apache syntax error
# - Connection refused
```

## 🔍 Verificaciones Locales

### Test 1: Página de Login
```bash
curl -I http://localhost:8080/login
# Esperado: 200 OK
```

### Test 2: Ruta inválida
```bash
curl -I http://localhost:8080/invalid-route
# Esperado: 404 (no 502)
```

### Test 3: Información de PHP
```bash
# Crear archivo temp
echo '<?php phpinfo(); ?>' > public/info.php

# Acceder a http://localhost:8080/info.php
# Verificar: PHP 8.2, pdo_mysql cargado, mod_rewrite activo

# Limpiar
rm public/info.php
```

## 🛑 Parar servicios
```bash
# Detener sin borrar datos
docker-compose down

# Detener y borrar todo (incluidas imágenes)
docker-compose down -v --rmi all
```

## 🐛 Troubleshooting Local

### Error: "Cannot access db from web"
```bash
# Verificar que la red existe
docker network ls

# Reiniciar servicios:
docker-compose restart
```

### Error: "MySQL port already in use (3306)"
```bash
# Matar proceso en puerto 3306
lsof -i :3306 | grep LISTEN
kill -9 <PID>

# O usar puerto diferente en docker-compose.yml:
# ports:
#   - "3307:3306"
```

### Error: "schema.sql not found"
```bash
# Verificar que database/schema.sql existe
ls -la database/

# Si falta, crear schema manualmente:
docker-compose exec db mysql -h localhost -u crm_user -psuper_secret_password restaurant_crm < database/schema.sql
```

### PHP muestra rutas completas (desarrollo)
**Esto es normal con APP_DEBUG=true**

Para producción, será deshabilitado automáticamente en Render (APP_DEBUG=false).

## 🎯 Flujo típico de testing

```bash
# 1. Iniciar servicios
docker-compose up --build

# 2. Esperar 15 segundos (MySQL inicializa)
# 3. Abrir http://localhost:8080
# 4. Probar login, crear registros
# 5. Ver logs: docker-compose logs -f
# 6. Cuando termines
docker-compose down
```

## 📝 Notas Importantes

- Los datos en MySQL se persisten en volumen `mysql-data`
- El código se monta en volumen (cambios se reflejan automáticamente)
- Los logs se mostrarán en tiempo real con `logs -f`
- Para resetear BD: `docker-compose down -v` (borra volumen)
