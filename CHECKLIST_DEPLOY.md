# ========================================================================
# VERIFICACIÓN PRE-DEPLOY CHECKLIST
# ========================================================================
# Ejecuta esto ANTES de hacer push a GitHub

## 1. VERIFICAR ESTRUCTURA DE CARPETAS
```bash
ls -la
# Debe mostrar:
# ✓ Dockerfile
# ✓ docker-entrypoint.sh (ejecutable)
# ✓ app/
# ✓ public/
# ✓ database/
# ✓ .htaccess-root (para desarrollo local)
# ✓ public/.htaccess (para producción)
# ✓ .env.example

# Verificar permisos
file docker-entrypoint.sh
# Debe indicar "executable"
```

## 2. VERIFICAR ARCHIVOS CRÍTICOS

### 2.1 Dockerfile
```bash
grep -E "EXPOSE 8080|Listen 8080|DocumentRoot" Dockerfile
```
**Esperado:**
```
EXPOSE 8080
Listen 8080
/var/www/html/public
```

### 2.2 docker-entrypoint.sh
```bash
head -5 docker-entrypoint.sh
# Debe empezar con: #!/bin/sh
```

### 2.3 public/.htaccess
```bash
grep "RewriteRule" public/.htaccess
# Debe contener: RewriteRule ^(.+)$ /index.php [QSA,L]
# (sin "public/" ni "?url=$1")
```

### 2.4 public/index.php
```bash
grep -A2 "REQUEST_URI" public/index.php
# Debe manejar normalización de URLs
```

## 3. VERIFICAR CONFIGURACIÓN LOCAL (opcional)

Si quieres probar localmente con Docker:
```bash
# Simular .env
touch .env
cat > .env << 'EOF'
DB_HOST=localhost
DB_PORT=3306
DB_NAME=restaurant_crm
DB_USER=root
DB_PASS=
APP_ENV=development
APP_DEBUG=true
EOF

# Construir imagen
docker build -t restaurant-crm:latest .

# Ejecutar contenedor
docker run -it --rm \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=3306 \
  -e DB_DATABASE=restaurant_crm \
  -e DB_USERNAME=root \
  -e DB_PASSWORD="" \
  -p 8080:8080 \
  restaurant-crm:latest

# Probar en el navegador
# http://localhost:8080
# http://localhost:8080/login
```

## 4. VERIFICAR GIT

```bash
git status
# No debe haber cambios sin commitear

git log --oneline -3
# Verificar commits recientes

git remote -v
# Verificar que origin apunta a GitHub
```

## 5. PUSH A GITHUB

```bash
git add .
git commit -m "Deploy configuration for Render"
git push origin main
```

## 6. VERIFICACIONES EN RENDER DASHBOARD (DESPUÉS DE DEPLOY)

### 6.1 Web Service Logs
```
Dashboard > restaurant-crm > Logs

Debería mostrar:
✓ "✓ .env generado correctamente"
✓ "DB_HOST: xxx.render.com"
✓ "Apache version..."
✗ NO debe mostrar "PHP Fatal error", "Connection refused", etc.
```

### 6.2 Base de Datos Activa
```
Dashboard > restaurant-crm-db > Overview

✓ Status: Available
✓ Conexión externa visible
✓ Database: restaurant_crm creada
```

### 6.3 Health Check
```
Dashboard > restaurant-crm > Deployments

✓ Latest deployment status = "Deployed"
✓ Health check passing
```

## 7. PRUEBAS FUNCIONALES

### 7.1 Accesibilidad
```bash
# Test 1: Home
curl -I https://restaurant-crm.onrender.com/
# Esperado: 200 OK (HTML de login)

# Test 2: Ruta protegida (sin autenticar)
curl -I https://restaurant-crm.onrender.com/dashboard
# Esperado: 302 (redirección a login, algunos devuelven 200)

# Test 3: Ruta que NO existe
curl -I https://restaurant-crm.onrender.com/invalid-route
# Esperado: 404 (no 502)
```

### 7.2 PHP Info (Opcional, borrar en producción)
```
Crea archivo temporal: public/info.php
<?php phpinfo(); ?>

Accede a https://restaurant-crm.onrender.com/info.php
✓ Debe mostrar información de PHP 8.2
✓ Extensión pdo_mysql cargada

BORRA public/info.php después para seguridad
```

### 7.3 Archivo de Prueba
```
Crea: public/test-db.php
<?php
require '../app/Core/DB.php';
try {
    $db = new \App\Core\DB();
    echo "✓ Conexión a DB exitosa!";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage();
}
?>

Accede a https://restaurant-crm.onrender.com/test-db.php
✓ Debe mostrar: "✓ Conexión a DB exitosa!"

BORRA public/test-db.php después para seguridad
```

## 8. MONITOREO POST-DEPLOY (PRIMERAS 24H)

- [ ] Revisar logs cada 4 horas
- [ ] Probar login con usuario de prueba
- [ ] Verificar que se crean registros en DB
- [ ] Monitorear errores en Render Dashboard

## 9. ROLLBACK (Si algo falla)

```bash
# Volver a commit anterior
git revert HEAD
git push origin main

# El deploy se actualizará automáticamente en ~5 min
```

---

## 📊 TABLA RESUMEN

| Componente | LocalHost | Render |
|-----------|-----------|--------|
| HTTP Port | 8080 (docker) | —— |
| HTTPS Port | —— | Automático |
| DocumentRoot | /public | /var/www/html/public |
| .htaccess | ✓ .htaccess-root (editar RewriteBase) | ✓ public/.htaccess |
| PHP Version | 8.2 | 8.2 |
| MySQL | Local/Ext | Render DB |
| Debugger | APP_DEBUG=true | APP_DEBUG=false |
