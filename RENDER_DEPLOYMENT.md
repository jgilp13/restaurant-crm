# 🚀 Guía Completa: Despliegue en Render

## 📋 Índice
1. [Preparación preliminar](#1-preparación-preliminar)
2. [Crear MySQL Database](#2-crear-mysql-database)
3. [Crear Web Service](#3-crear-web-service)
4. [Configurar Variables de Entorno](#4-configurar-variables-de-entorno)
5. [Verificación Post-Deploy](#5-verificación-post-deploy)
6. [Troubleshooting](#6-troubleshooting)

---

## 1. Preparación Preliminar

### 1.1 Verificar que el código esté en GitHub
```bash
# En tu máquina (local)
git remote -v
# Debe mostrar origin pointing a tu repo en GitHub
```

**Posibles rutas:**
- https://github.com/tu-usuario/restaurant-crm
- git@github.com:tu-usuario/restaurant-crm

Si no tienes GitHub:
1. Crea una cuenta en https://github.com/join
2. Crea un repositorio nuevo
3. Publica tu código:
```bash
git remote add origin https://github.com/tu-usuario/restaurant-crm
git branch -M main
git push -u origin main
```

### 1.2 Verificar Dockerfile y archivos críticos
```bash
# Debe existir en la raíz:
# ✓ Dockerfile
# ✓ docker-entrypoint.sh
# ✓ render.yaml
# ✓ .htaccess (en /public)
```

---

## 2. Crear MySQL Database

### 2.1 Acceder a Render Dashboard
- Ir a https://dashboard.render.com
- Inicia sesión con GitHub (recomendado)

### 2.2 Crear MySQL Database
1. Click en **New +** (esquina superior derecha)
2. Selecciona **MySQL**
3. Rellena:
   - **Name**: `restaurant-crm-db` (o como prefieras)
   - **Database**: `restaurant_crm`
   - **User**: `crm_user`
   - **Plan**: `Free` (para testing) o `Standard` (producción)
4. Click en **Create MySQL Instance**

### 2.3 Copiar datos de conexión
**Después de crear la DB, verás algo como esto:**
```
Render URL (MySQL):  mysql://crm_user:secretpassword@dpf-xxxxx.c10.render.com:3306/restaurant_crm
```

**Anota estos valores** (los necesitarás luego):
- **DB_HOST**: `dpf-xxxxx.c10.render.com`
- **DB_PORT**: `3306`
- **DB_DATABASE**: `restaurant_crm`
- **DB_USERNAME**: `crm_user`
- **DB_PASSWORD**: `secretpassword`

### 2.4 Cargar Schema (Primera vez)
Dentro de Render Dashboard de tu DB:
1. Click en la pestaña **Connections**
2. Click en **PSQL** (abre terminal MySQL)
3. Ejecuta:
```sql
-- Pegar contenido de database/schema.sql
SOURCE /ruta/a/schema.sql;

-- O manualmente:
USE restaurant_crm;
-- Pegar las tablas del schema.sql
```

Alternativa: Usar MySQL Workbench o phpMyAdmin para cargar `database/schema.sql`

---

## 3. Crear Web Service

### 3.1 Conectar GitHub a Render
1. En https://dashboard.render.com, click **New +**
2. Selecciona **Web Service**
3. Selecciona **GitHub** como source (si no está conectado, autoriza Render)
4. Busca y selecciona `restaurant-crm` repo
5. Rellena:
   - **Name**: `restaurant-crm` (o similar)
   - **Region**: Elige la más cercana a tus usuarios (ej: Ohio, Frankfurt)
   - **Branch**: `main`
   - **Runtime**: `Docker`
   - **Instance Type**: `Free` (testing) o `Standard` (producción)

### 3.2 Build & Deploy Settings
El Dockerfile se detectará automáticamente. Verifica:
- ✓ **Dockerfile path**: `./Dockerfile` (automático)
- ✓ **Docker Command**: Déjalo vacío (usa ENTRYPOINT del Dockerfile)

Click en **Deploy** (o espera a que aparezca el botón)

---

## 4. Configurar Variables de Entorno

### 4.1 Acceder a las Environment Variables del Web Service
1. En tu Web Service `restaurant-crm` dentro de Render
2. Click en la pestaña **Environment**
3. Añade estas variables:

#### Configuración Base
```
APP_ENV              = production
APP_DEBUG            = false
```

#### Configuración de Base de Datos
```
DB_HOST              = dpf-xxxxx.c10.render.com
DB_PORT              = 3306
DB_DATABASE          = restaurant_crm
DB_USERNAME          = crm_user
DB_PASSWORD          = tu_contraseña_segura
```

**💡 Recomendación**: Copialas directamente del **Internal Database URL** de tu MySQL:
- `mysql://crm_user:password@host:3306/restaurant_crm`

### 4.2 Variables Adicionales (Opcionales)
```
ITEMS_PER_PAGE       = 10
TZ                   = America/Mexico_City
```

### 4.3 Guardar y Redeploy
1. Click en **Save** (en cada variable)
2. Espera a que terminen todos
3. El servicio se redesplegará automáticamente ✓

---

## 5. Verificación Post-Deploy

### 5.1 Comprobar Status
En tu Web Service de Render:
1. Busca la sección **Latest Deployment**
2. Estado debe ser **Deployed** ✓
3. Aparecerá una URL como: `https://restaurant-crm.onrender.com`

### 5.2 Pruebas en el navegador
```
https://restaurant-crm.onrender.com/           → Login page ✓
https://restaurant-crm.onrender.com/dashboard  → Redirige a login (correcto) ✓
https://restaurant-crm.onrender.com/restaurants → Redirige a login ✓
```

### 5.3 Ver logs en tiempo real
En Render Dashboard de tu Web Service:
1. Click en pestaña **Logs**
2. Verás output de Apache y PHP en tiempo real
3. Busca errores: `Error`, `Exception`, `FATAL`

### 5.4 Test de base de datos
En tu navegador:
```
https://restaurant-crm.onrender.com/login
```

Si necesitas crear usuarios para probar:
1. Usa un cliente MySQL (MySQL Workbench, DBeaver, etc.)
2. Conéctate con credenciales de Render
3. Inserta usuarios en tabla `users`

```sql
INSERT INTO users (name, email, password, created_at) 
VALUES ('Admin', 'admin@example.com', PASSWORD('123456'), NOW());
```

---

## 6. Troubleshooting

### ❌ Error 502 Bad Gateway
**Causas comunes:**
- Docker no se compila
- Puerto incorrecto (debe ser 8080)
- PHP/Apache no inicia

**Solución:**
1. Ve a **Logs** y busca el error exacto
2. Verifica que `Dockerfile` tenga `EXPOSE 8080`
3. Verifica que Apache escuche en 8080: `Listen 8080` en `ports.conf`

### ❌ Error 404 en /login (pero /dashboard funciona)
**Causa:** `.htaccess` no está configurado correctamente

**Solución:**
```bash
# Verifica que /public/.htaccess tenga:
# RewriteRule ^(.+)$ /index.php [QSA,L]
# NO debe incluir "?url=$1"
```

### ❌ Error "Cannot find module"
**Causa:** Falta copiar carpeta `app/` o módulos PHP

**Solución:**
```dockerfile
# En Dockerfile, verifica:
COPY . /var/www/html  # ← Debe copiar TODO
```

### ❌ Base de datos conecta pero las tablas no existen
**Causa:** `schema.sql` no se ejecutó

**Solución:**
1. Usa Render Dashboard → MySQL → **PSQL tab**
2. Pega el contenido de `database/schema.sql`
3. Presiona Enter

### ❌ Error "HTTPS not working" (mostrado en navegador)
**Normal en Render:**
- Render automáticamente redirige HTTP → HTTPS
- El php revela el protocolo correcto gracias a:
```php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
```

### ⚠️ Rendimiento lento
- El plan **Free** de Render puede hibernar (inactividad)
- Usa **Standard** para producción
- MySQL Free también es muy limitado

---

## 🎯 Resumen de URLs Importantes

| Recurso | URL |
|---------|-----|
| Dashboard Render | https://dashboard.render.com |
| Tu App | https://restaurant-crm.onrender.com |
| Logs | Dashboard > restaurant-crm > Logs |
| MySQL DB | Dashboard > restaurant-crm-db > Connections |
| GitHub Integration | Account > GitHub Repos |

---

## ✅ Checklist Final de Deploy

- [ ] Código subido a GitHub (`main` branch)
- [ ] Dockerfile verificado (EXPOSE 8080, Listen 8080)
- [ ] docker-entrypoint.sh ejecutable
- [ ] .htaccess en /public configurado
- [ ] MySQL Database creada en Render
- [ ] Database URL copiada correctamente
- [ ] Web Service creado y conectado a GitHub
- [ ] Variables de entorno configuradas:
  - [ ] APP_ENV
  - [ ] APP_DEBUG
  - [ ] DB_HOST
  - [ ] DB_PORT
  - [ ] DB_DATABASE
  - [ ] DB_USERNAME
  - [ ] DB_PASSWORD
- [ ] Web Service desplegado exitosamente
- [ ] Schema.sql ejecutado en MySQL
- [ ] Test /login accesible sin 404
- [ ] Logs sin errores FATAL
- [ ] HTTPS funciona correctamente

---

## 📞 Soporte Render
- Docs: https://render.com/docs
- Status: https://status.render.com
- Email: support@render.com
