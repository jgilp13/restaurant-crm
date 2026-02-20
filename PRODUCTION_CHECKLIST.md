# 🎯 CHECKLIST FINAL - PRODUCCIÓN EN RENDER

## ✅ PASO 0: PREPARACIÓN (Local)

### 0.1 Archivos Críticos Verificados
- [ ] `Dockerfile` contiene `EXPOSE 8080`
- [ ] `Dockerfile` contiene `Listen 8080`
- [ ] `docker-entrypoint.sh` es ejecutable (`chmod +x docker-entrypoint.sh` si no)
- [ ] `public/.htaccess` existe y tiene RewriteRule correctas
- [ ] `app/Core/DB.php` usa constantes: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
- [ ] `.env.example` existe (opcional pero buena práctica)

### 0.2 Verificación Local (Opcional pero RECOMENDADO)
```bash
docker-compose up --build
# Esperar 30 segundos
curl http://localhost:8080/login
# Debe devolver HTML 200, no 502 ni 404
docker-compose down
```

### 0.3 Git Push
```bash
git add .
git commit -m "Production configuration for Render deployment"
git push origin main
# Esperar a que GitHub confirme el push
```

---

## ✅ PASO 1: CREAR MYSQL DATABASE EN RENDER

### 1.1 Acceder a Render
- [ ] Va a https://dashboard.render.com
- [ ] Inicia sesión (usa GitHub si aún no tienes cuenta)

### 1.2 Crear Base de Datos MySQL
- [ ] Click en **New +** (esquina superior derecha)
- [ ] Selecciona **MySQL**
- [ ] Rellena:
  ```
  Name:       restaurant-crm-db
  Database:   restaurant_crm
  User:       crm_user
  Plan:       Free (testing) o Standard (producción)
  ```
- [ ] Click en **Create MySQL Instance**
- [ ] Espera 2-3 minutos (estatus: Available ✓)

### 1.3 Copiar Credenciales
**En la página de tu DB, busca:**
```
Render URL (MySQL):
mysql://crm_user:PASSWORD@HOST:3306/restaurant_crm
```

**Anota estos valores:**
```
DB_HOST = HOST (ej: mysql.xxx.onrender.com)
DB_PORT = 3306
DB_DATABASE = restaurant_crm
DB_USERNAME = crm_user
DB_PASSWORD = PASSWORD (ej: abc123xyz...)
```

### 1.4 Cargar Schema
En Render DB Dashboard:
- [ ] Click en pestaña **Connections**
- [ ] Abre PSQL (terminal MySQL)
- [ ] Copia y pega todo el contenido de `database/schema.sql`
- [ ] Presiona Enter
- [ ] Verifica `SHOW TABLES;` muestra tus tablas

**Alternativa:** Usa MySQL Workbench o navicat local para conectar y cargar schema.sql

---

## ✅ PASO 2: CREAR WEB SERVICE EN RENDER

### 2.1 Crear Web Service
- [ ] En Dashboard Render, click **New +**
- [ ] Selecciona **Web Service**
- [ ] Conecta con GitHub (si no está conectado, autoriza)
- [ ] Selecciona repo `restaurant-crm`
- [ ] Rellena:
  ```
  Name:           restaurant-crm
  Region:         Ohio (USA), Frankfurt (EU), etc.
  Branch:         main
  Runtime:        Docker
  Instance Type:  Free (testing) o Standard (prod)
  ```
- [ ] Click en **Deploy**

### 2.2 Esperar Construcción
- [ ] Status cambia: Building → Deploying → Deployed
- [ ] Puede tardar 3-5 minutos
- [ ] **NO hagas cambios mientras construye**

---

## ✅ PASO 3: CONFIGURAR VARIABLES DE ENTORNO

### 3.1 Abrir Configuración
En tu Web Service `restaurant-crm`:
- [ ] Click en pestaña **Environment**

### 3.2 Agregar Variables (Una por una)

**Configuración Base:**
```
APP_ENV      = production
APP_DEBUG    = false
```

**Base de Datos (desde tus anotaciones de Paso 1.3):**
```
DB_HOST      = mysql.c99.onrender.com        (tu valor exacto)
DB_PORT      = 3306
DB_DATABASE  = restaurant_crm
DB_USERNAME  = crm_user
DB_PASSWORD  = {tu_contraseña_segura}
```

### 3.3 Guardar y Esperar Redeploy
- [ ] Cada variable debe tener ✓ guardado
- [ ] El servicio se redeploya automáticamente
- [ ] Espera a que status sea **Deployed**

---

## ✅ PASO 4: VERIFICACIÓN POST-DEPLOY

### 4.1 Comprobar Status
En Web Service:
- [ ] **Latest Deployment** muestra **Deployed** ✓
- [ ] **URL** muestra algo como: `https://restaurant-crm.onrender.com`
- [ ] No hay errores en sección **Build Logs**

### 4.2 Ver Logs en Tiempo Real
En pestaña **Logs**:
```
✓ Debe mostrar lineas como:
  "✓ .env generado correctamente"
  "DB_HOST: mysql.c99.onrender.com"
  "DB_DATABASE: restaurant_crm"
  "Apache/2.4.x (Ubuntu) started"

✗ NO debe mostrar:
  "FATAL", "ERROR", "Connection refused"
```

### 4.3 Pruebas en Navegador
- [ ] `https://restaurant-crm.onrender.com` → Accesible (login page) ✓
- [ ] `https://restaurant-crm.onrender.com/login` → Mismo resultado ✓
- [ ] `https://restaurant-crm.onrender.com/dashboard` → Redirige a login o muestra contenido ✓
- [ ] `https://restaurant-crm.onrender.com/invalid-route` → 404 (no 502) ✓

### 4.4 Test Avanzado (Opcional)
```bash
# Desde tu terminal local:

# Test 1: Verificar HTTPS
curl -I https://restaurant-crm.onrender.com/
# Esperado: HTTP/2 200 (o HTTP/1.1 200)

# Test 2: Verificar .htaccess
curl -I https://restaurant-crm.onrender.com/login
# Esperado: 200 (no 404)

# Test 3: Archivo inexistente
curl -I https://restaurant-crm.onrender.com/xyz789abc
# Esperado: 404
```

---

## ✅ PASO 5: TROUBLESHOOTING (Si algo falla)

### 5.1 Error 502 Bad Gateway
**Causa probable:** Docker no compila o Apache no inicia

**Solución:**
1. Ve a Web Service → Logs
2. Busca el error exacto (FATAL, ERROR, etc.)
3. Revisa Dockerfile:
   - ¿Contiene `EXPOSE 8080`?
   - ¿Contiene `Listen 8080` en ports.conf?
   - ¿docker-entrypoint.sh es válido?
4. Haz correctivo, commit, push → Auto-redeploy

### 5.2 Error 404 en `/login` pero `http://localhost:8080/login` funciona local
**Causa:** .htaccess no está configurado correctamente

**Solución:**
1. Ve a public/.htaccess
2. Verifica que tenga:
   ```
   RewriteRule ^(.+)$ /index.php [QSA,L]
   ```
3. NO debe tener:
   - `RewriteBase /restaurant-crm/`
   - `?url=$1`
4. Commit y push → Auto-redeploy

### 5.3 Error de conexión a BD
**Causa:** Variables de entorno mal mapeadas o credenciales incorrectas

**Solución:**
1. Verifica en Dashboard:
   - DB_HOST está exacto (copia desde MySQL panel)
   - DB_USERNAME = crm_user
   - DB_PASSWORD coincide
2. Crea archivo test temporal:
   ```php
   // public/test-db.php
   <?php
   echo "DB_HOST: " . getenv('DB_HOST') . "\n";
   echo "DB_USER: " . getenv('DB_USERNAME') . "\n";
   // etc.
   ?>
   ```
3. Accede a `https://tu-app.onrender.com/test-db.php`
4. Verifica que los valores sean correctos
5. **Borra test-db.php** cuando termines

### 5.4 HTTPS no funciona
**Nota:** En Render es **automático**, no necesitas hacer nada
- SI el navegador te muestra advertencia SSL: Espera 5 minutos más (certificado LetsEncrypt se está provisionar)
- Si persiste después de 10 min: Contacta a soporte Render

### 5.5 Páginas se cargan pero sin estilos/imágenes
**Causa:** Rutas de assets incorrectas

**Verifica en tu layout:**
```php
<link rel="stylesheet" href="/assets/css/style.css">          ✓ Correcto
<link rel="stylesheet" href="assets/css/style.css">          ✗ Incorrecto
<script src="<?= BASE_URL ?>assets/js/app.js"></script>      ✓ Mejor
```

---

## ✅ PASO 6: MONITOREO (Primeras 24 horas)

### 6.1 Revisar Logs Regularmente
- [ ] Cada 4 horas, chequea Render Dashboard → Logs
- [ ] Busca `ERROR`, `FATAL`, `exception`
- [ ] Si hay errores, corrige y haz commit + push

### 6.2 Probar Funcionalidades Principales
- [ ] Página de login carga ✓
- [ ] Login con usuario de prueba funciona ✓
- [ ] Crear/editar restaurante funciona ✓
- [ ] Los datos se guardan en BD ✓

### 6.3 Monitoreo de Uso
- [ ] Dashboard → Metrics muestra uso de CPU/Memoria
- [ ] Si CPU > 80% constantemente: Actualiza a plan Standard
- [ ] Si memoria limitada: Optimiza consultas SQL

---

## ✅ PASO 7: LIMPIEZA Y SEGURIDAD

### 7.1 Eliminar Archivos de Prueba (si creaste)
```bash
rm public/test-db.php public/info.php
git add .
git commit -m "Remove test files"
git push origin main
```

### 7.2 Verificar Seguridad
- [ ] `public/.htaccess` bloquea `/app`, `/database`, `/vendor` ✓
- [ ] `.env` NO está en repositorio (verificar .gitignore)
- [ ] Variables sensibles (DB_PASSWORD) están SOLO en Render Environment ✓
- [ ] APP_DEBUG = false en producción ✓

### 7.3 Configurar Backup (Opcional pero RECOMENDADO)
En MySQL Database de Render Dashboard:
- [ ] Busca sección **Backups**
- [ ] Habilita backups automáticos (si está disponible en tu plan)

---

## 🚨 ROLLBACK DE EMERGENCIA

Si algo está muy mal y quieres volver atrás:

```bash
# Local:
git log --oneline | head -5
git revert HEAD
git push origin main

# Render auto-se-redeploya (3-5 minutos)
```

---

## 📊 CHECKLIST RESUMIDO

Marca estas 3 cosas cuando esté todo listo:

- [ ] **MySQL Database creada** en Render (Status: Available)
- [ ] **Web Service desplegado** en Render (Status: Deployed)
- [ ] **Variables de entorno configuradas** (DB_HOST, DB_USERNAME, DB_PASSWORD, APP_ENV, APP_DEBUG)
- [ ] **Test /login funciona** (sin 404)
- [ ] **Logs limpios** (sin FATAL/ERROR)

---

## 🎉 ¡LISTO PARA PRODUCCIÓN!

Cuando hayas completado todos los pasos:

1. Tu app está en `https://restaurant-crm.onrender.com` 🚀
2. Base de datos está sincronizada ✓
3. HTTPS está habilitado automáticamente ✓
4. .htaccess redirige correctamente ✓
5. Los usuarios pueden hacer login ✓

### Próximos pasos opcionales:
- [ ] Configurar dominio personalizado (en Render Settings)
- [ ] Configurar email (SendGrid, Mailgun, etc.)
- [ ] Añadir analytics (Google Analytics, etc.)
- [ ] Configurar alertas en Render

---

**Documentos de referencia:**
- 📖 Guía detallada: `RENDER_DEPLOYMENT.md`
- 🐳 Testing local: `DOCKER_LOCAL_TESTING.md`
- 📝 Resumen cambios: `DEPLOY_SUMMARY.md`
