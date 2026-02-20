# 📋 VALIDACIÓN RÁPIDA PRE-PUSH

Ejecuta esto antes de hacer `git push origin main`:

## 1️⃣ VALIDAR DOCKERFILE

```bash
grep -n "EXPOSE 8080\|Listen 8080\|/var/www/html/public" Dockerfile
```

**Esperado:**
```
8: EXPOSE 8080
22: Listen 8080
(varias líneas con /var/www/html/public)
```

---

## 2️⃣ VALIDAR docker-entrypoint.sh

```bash
file docker-entrypoint.sh
```

**Esperado:** `executable` 

Si NO es ejecutable:
```bash
chmod +x docker-entrypoint.sh
```

---

## 3️⃣ VALIDAR public/.htaccess

```bash
cat public/.htaccess | grep RewriteRule
```

**Esperado:**
```
RewriteRule ^(.+)$ /index.php [QSA,L]
RewriteRule ^$ index.php [QSA,L]
```

**❌ NO debe contener:**
- `RewriteBase /restaurant-crm/`
- `?url=$1`

---

## 4️⃣ VALIDAR app/Core/DB.php

```bash
grep -n "DB_HOST\|DB_NAME\|DB_USER\|DB_PASS" app/Core/DB.php | head -5
```

**Esperado:** Referencias a constantes, ej:
```
'host' => DB_HOST,
'database' => DB_NAME,
'user' => DB_USER,
'pass' => DB_PASS,
```

---

## 5️⃣ VALIDAR archivos nuevos existen

```bash
ls -la RENDER_DEPLOYMENT.md CHECKLIST_DEPLOY.md DOCKER_LOCAL_TESTING.md docker-compose.yml DEPLOY_SUMMARY.md PRODUCTION_CHECKLIST.md
```

**Esperado:** 6 archivos listados sin errores

---

## 6️⃣ TEST LOCAL (OPCIONAL pero RECOMENDADO)

```bash
# Construcción
docker-compose up --build

# ESPERADO en logs:
# - "✓ .env generado correctamente"
# - "restaurant-crm-db | ready for connections"

# EN OTRA TERMINAL:
curl -I http://localhost:8080
curl -I http://localhost:8080/login

# ESPERADO: "200 OK" para ambas

# FINALMENTE:
docker-compose down
```

---

## 7️⃣ GIT STATUS

```bash
git status
```

**Esperado:** Todos los cambios están staged o en status limpio

```bash
git add .
git commit -m "Production configuration for Render: Docker, Apache rewrite, env vars"
git push origin main
```

---

## ✅ LISTO PARA PUSH

Si todos los pasos arriba pasaron:

```bash
git push origin main
```

**Después:** 
- [ ] Ir a Render Dashboard
- [ ] Seguir `PRODUCTION_CHECKLIST.md`
