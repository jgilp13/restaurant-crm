# ⚡ CHEAT SHEET - Comandos Rápidos

Copia/pega estos comandos según necesites.

---

## 🚀 DEPLOY RÁPIDO (Sin testing local)

```bash
# 1. Validar cambios (3 min)
grep -E "EXPOSE 8080|Listen 8080" Dockerfile
grep "RewriteRule ^(.+)$" public/.htaccess
file docker-entrypoint.sh  # Debe ser: executable

# 2. Push a GitHub (1 min)
git add .
git commit -m "Production configuration for Render"
git push origin main

# 3. Luego: Ve a PRODUCTION_CHECKLIST.md (20 min)
```

---

## 🐳 TESTING LOCAL (Con Docker)

```bash
# 1. Construir y ejecutar
docker-compose up --build

# 2. En otra terminal - Ver logs
docker-compose logs -f web

# 3. Probar en navegador
curl -I http://localhost:8080/login

# 4. Probar base de datos
docker-compose exec db mysql -h localhost -u crm_user -psuper_secret_password restaurant_crm -e "SHOW TABLES;"

# 5. Cuando termines
docker-compose down
```

---

## 🔍 VALIDAR ARCHIVOS CRÍTICOS

```bash
# Verificar Dockerfile
echo "=== Dockerfile ===" && \
grep "EXPOSE 8080" Dockerfile && \
grep "Listen 8080" Dockerfile && \
echo "✓ Dockerfile OK"

# Verificar docker-entrypoint.sh
echo "=== docker-entrypoint.sh ===" && \
file docker-entrypoint.sh && \
head -1 docker-entrypoint.sh

# Verificar .htaccess
echo "=== public/.htaccess ===" && \
grep -n "RewriteRule" public/.htaccess
```

---

## 📋 LISTA DE VERIFICACIÓN PRE-PUSH

```bash
# Ejecuta esto antes de git push
echo "1. Verificando Dockerfile..."
grep "EXPOSE 8080" Dockerfile > /dev/null && echo "✓" || echo "✗"

echo "2. Verificando puerto 8080..."
grep "Listen 8080" Dockerfile > /dev/null && echo "✓" || echo "✗"

echo "3. Verificando .htaccess..."
grep "RewriteRule ^(.+)$" public/.htaccess > /dev/null && echo "✓" || echo "✗"

echo "4. Verificando docker-entrypoint.sh es ejecutable..."
[ -x docker-entrypoint.sh ] && echo "✓" || echo "✗"

echo "5. Verificando archivos nuevos..."
[ -f PRODUCTION_CHECKLIST.md ] && echo "✓" || echo "✗"

echo ""
echo "Si ves todos ✓, puedes hacer: git push origin main"
```

---

## 🔧 VALIDACIONES EN RENDER DASHBOARD

### Test 1: ¿La app está disponible?
```bash
curl -I https://restaurant-crm.onrender.com
# Esperado: HTTP/2 200
```

### Test 2: ¿/login funciona (no 404)?
```bash
curl -I https://restaurant-crm.onrender.com/login
# Esperado: HTTP/2 200
```

### Test 3: ¿Las rutas inválidas dan 404?
```bash
curl -I https://restaurant-crm.onrender.com/invalid-xyz
# Esperado: HTTP/2 404
```

### Test 4: ¿Base de datos está conectando?
```bash
# En Render Dashboard > SQL tab:
SHOW TABLES;
# Debe listar: users, restaurants, menus, etc.
```

---

## 🐛 TROUBLESHOOTING RÁPIDO

### Error 502 Bad Gateway
```bash
# Paso 1: Ver logs
# Render Dashboard > tu-app > Logs

# Paso 2: Buscar error
# Busca: "FATAL", "ERROR", "Connection refused"

# Paso 3: Soluciones comunes
# - ¿Dockerfile tiene EXPOSE 8080? Sí
# - ¿docker-entrypoint.sh es válido? Sí
# - ¿Apache puede iniciar? Ver logs

# Paso 4: Redeployar
git push origin main  # Auto-redeploy en 3-5 min
```

### Error 404 en /login
```bash
# Paso 1: Revisar public/.htaccess
grep "RewriteRule" public/.htaccess

# CORRECTO: ^(.+)$ /index.php
# INCORRECTO: public/index.php o ?url=$1

# Paso 2: Si está mal, corregir
# Editar public/.htaccess
git add .
git push origin main

# Paso 3: Verificar
curl -I https://restaurant-crm.onrender.com/login  # Debe ser 200
```

### Error no encuentra tabla
```bash
# Paso 1: Verificar en MySQL que tabla existe
# Render Dashboard > MySQL > PSQL tab
SHOW TABLES;

# Paso 2: Si no existen, cargar schema.sql
# Copiar contenido de database/schema.sql
# Pegarlo en PSQL tab

# Paso 3: Verificar
SHOW TABLES;  # Debe listar tablas
```

---

## 🔌 VARIABLES DE ENTORNO EN RENDER

```bash
# Render Dashboard > tu-web-service > Environment

# Copiar exactamente así (desde MySQL panel):
APP_ENV=production
APP_DEBUG=false
DB_HOST=mysql.c99.onrender.com      # (tu valor)
DB_PORT=3306
DB_DATABASE=restaurant_crm
DB_USERNAME=crm_user
DB_PASSWORD=xyz789...                 # (tu valor exacto)
```

---

## 📊 VERIFICAR LOGS EN RENDER

```bash
# Render Dashboard > tu-web-service > Logs

# Buscar lo BUENO:
"✓ .env generado correctamente"
"Apache/2.4 configured"
"listening on port 8080"

# Buscar lo MALO:
"ERROR"
"FATAL"
"Connection refused"
"Segmentation fault"
```

---

## 🔐 COMANDOS DE SEGURIDAD

```bash
# Verificar que .env NO está en Git
git status | grep ".env"
# No debe aparecer

# Verificar que .env está en .gitignore
grep ".env" .gitignore
# Debe existir

# Verificar que archivos sensibles están bloqueados
grep "FilesMatch.*env\|database/\|vendor/" public/.htaccess
# Debe existir
```

---

## 🎯 COMANDOS FINALES

```bash
# Resumen: ¿Estoy listo para deploy?
echo "=== PRE-DEPLOY CHECKLIST ===" && \
echo "1. Cambios:" && git status && \
echo "" && \
echo "2. Remoto:" && git remote -v && \
echo "" && \
echo "3. git push:" && \
echo "git push origin main" && \
echo "" && \
echo "4. Luego: Ve a Render Dashboard y sigue PRODUCTION_CHECKLIST.md"
```

---

## 🚀 UNA SOLA ORDEN PARA TODO

```bash
# Copiar y ejecutar esta línea (hace todos los checks):
echo "1. Validar..." && \
grep "EXPOSE 8080" Dockerfile > /dev/null && grep "Listen 8080" Dockerfile > /dev/null && \
grep "RewriteRule ^(.+)$" public/.htaccess > /dev/null && \
[ -x docker-entrypoint.sh ] && \
echo "✓ Todos los checks pasaron" && \
echo "2. Ahora ejecuta: git push origin main"
```

---

## 📱 TESTING DESDE MOBILE

```bash
# Cuando esté en producción, probar desde teléfono:

# Si tu app está en: https://restaurant-crm.onrender.com

# Desde mobile browser:
# 1. Abre: https://restaurant-crm.onrender.com
# 2. Debe verse: Página de login
# 3. Intenta login
# 4. Verifica estilos se cargan bien (no se ve feo)
```

---

## 🧹 LIMPIEZA DESPUÉS DE DEPLOY

```bash
# Si creaste archivos de test, borrarlos:
rm -f public/test-db.php public/info.php

# Commit de limpieza
git add .
git commit -m "Remove test files"
git push origin main
```

---

## 📞 SOPORTE RÁPIDO

```bash
# Si todo falla, opción nuclear:

# 1. Deshacerse el último commit
git revert HEAD
git push origin main

# 2. Esperar 3-5 minutos
# Le red-deploya automáticamente

# 3. Volver a intentar después
```

---

## 📚 DOCUMENTACIÓN RÁPIDA

| Necesito... | Comando/Link |
|------------|--------------|
| Entender qué cambió | `cat CHANGES_SUMMARY.md` |
| Desplegar ahora | `cat PRODUCTION_CHECKLIST.md` |
| Testear local | `docker-compose up --build` |
| Ver logs | `docker-compose logs -f web` |
| Validar pre-push | `cat VALIDATION_QUICK.md` |
| Entender arquitectura | `cat ARCHITECTURE.md` |
| Todos los docs | `cat DOCUMENTATION_INDEX.md` |

---

**Print this page and keep it near while deploying! 📟**
