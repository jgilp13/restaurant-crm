# 🏗️ ARQUITECTURA EN PRODUCCIÓN

## Diagrama de Flujo: De Usuario a Base de Datos

```
┌─────────────────────────────────────────────────────────────────────┐
│ USUARIO EN NAVEGADOR                                                │
│ https://restaurant-crm.onrender.com/login                           │
└────────────────────┬────────────────────────────────────────────────┘
                     │ HTTPS (Automático Render)
                     ▼
┌─────────────────────────────────────────────────────────────────────┐
│ RENDER EDGE (Reverse Proxy)                                         │
│ • Termina HTTPS                                                     │
│ • Redirecciona HTTP → HTTPS                                        │
│ • Agrega header: X-Forwarded-Proto: https                         │
└────────────────────┬────────────────────────────────────────────────┘
                     │ HTTP interno (rápido)
                     ▼
┌─────────────────────────────────────────────────────────────────────┐
│ TU CONTENEDOR RENDER (Docker)                                      │
│ IP interna: 172.17.0.2 (ejemplo)                                   │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌──────────────────────────────────┐                              │
│  │ Apache + PHP 8.2                 │                              │
│  │ Puerto: 8080 (expuesto)          │                              │
│  │                                  │                              │
│  │ 1. docker-entrypoint.sh          │                              │
│  │    └─ Genera .env                │                              │
│  │    └─ Lee: DB_HOST, DB_USERNAME  │                              │
│  │    └─ Inicia Apache               │                              │
│  │                                  │                              │
│  │ 2. Apache recibe request:8080    │                              │
│  │    └─ DocumentRoot: /public     │                              │
│  │    └─ Lee .htaccess              │                              │
│  │    └─ Rewrite: ^(.+)$ /index.php │                              │
│  │                                  │                              │
│  │ 3. index.php ejecuta:            │                              │
│  │    └─ Detecta HTTPS (del header) │                              │
│  │    └─ Carga .env (variables)     │                              │
│  │    └─ Autoload clases            │                              │
│  │    └─ Router.php mapea URL       │                              │
│  │    └─ Ejecuta Controller          │                              │
│  │                                  │                              │
│  │ 4. Controller usa Model:         │                              │
│  │    └─ App/Models/User.php        │                              │
│  │    └─ Ejecuta query SQL          │                              │
│  │                                  │                              │
│  └──────────────────────────────────┘                              │
│           │                                                         │
│           │ usa constantes: DB_HOST, DB_USER, DB_PASS             │
│           │           (desde .env creado en docker-entrypoint)    │
│           ▼                                                         │
│  ┌──────────────────────────────────┐                              │
│  │ PDO MySQL Connection             │                              │
│  │ Port 3306 (TCP)                  │                              │
│  └──────────────────────────────────┘                              │
│           │                                                         │
└───────────┼────────────────────────────────────────────────────────┘
            │
            │ Conexión MySQL (encriptada)
            ▼
┌─────────────────────────────────────────────────────────────────────┐
│ RENDER MYSQL DATABASE                                              │
│ Host: mysql.c99.onrender.com                                       │
│ Port: 3306                                                         │
│ Database: restaurant_crm                                           │
│                                                                      │
│ Tablas:                                                             │
│ • users          (login)                                            │
│ • restaurants    (CRUD)                                             │
│ • menu_items     (menuítems)                                        │
│ • categories     (categorías)                                       │
│ • leads          (leads CRM)                                        │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Variables de Entorno: Flujo Completo

```
┌─────────────────────────────────────────────────────────────┐
│ RENDER DASHBOARD                                            │
│ Web Service > Environment                                  │
│                                                             │
│ APP_ENV                = production                        │
│ APP_DEBUG              = false                             │
│ DB_HOST                = mysql.c99.onrender.com            │
│ DB_PORT                = 3306                              │
│ DB_DATABASE            = restaurant_crm                    │
│ DB_USERNAME            = crm_user                          │
│ DB_PASSWORD            = xyz789...                         │
└────────────────┬────────────────────────────────────────────┘
                 │ Render inyecta como env vars del contenedor
                 ▼
┌─────────────────────────────────────────────────────────────┐
│ CONTENEDOR DOCKER                                           │
│ mkdir -p /var/www/html                                     │
│                                                             │
│ docker-entrypoint.sh EJECUTA:                             │
│                                                             │
│ #!/bin/sh                                                 │
│ DB_HOST="${DB_HOST:-localhost}"                           │
│ DB_DATABASE="${DB_DATABASE:-restaurant_crm}"             │
│ DB_USERNAME="${DB_USERNAME:-root}"                       │
│ DB_PASSWORD="${DB_PASSWORD:-}"                           │
│                                                             │
│ cat > "/var/www/html/.env" << EOF                        │
│ DB_HOST=$DB_HOST                                          │
│ DB_PORT=$DB_PORT                                          │
│ DB_NAME=$DB_DATABASE                                      │
│ DB_USER=$DB_USERNAME                                      │
│ DB_PASS=$DB_PASSWORD                                      │
│ APP_ENV=$APP_ENV                                          │
│ APP_DEBUG=$APP_DEBUG                                      │
│ EOF                                                        │
│                                                             │
│ exec apache2-foreground                                   │
└────────────────┬────────────────────────────────────────────┘
                 │ Crea archivo .env
                 ▼
┌─────────────────────────────────────────────────────────────┐
│ /var/www/html/.env (Generado)                             │
│                                                             │
│ DB_HOST=mysql.c99.onrender.com                           │
│ DB_PORT=3306                                               │
│ DB_NAME=restaurant_crm                                     │
│ DB_USER=crm_user                                           │
│ DB_PASS=xyz789...                                          │
│ APP_ENV=production                                         │
│ APP_DEBUG=false                                            │
└────────────────┬────────────────────────────────────────────┘
                 │ PHP lee con parse_ini_file()
                 ▼
┌─────────────────────────────────────────────────────────────┐
│ /public/index.php                                           │
│                                                             │
│ $envFile = ROOT_PATH . '/.env';                           │
│ if (file_exists($envFile)) {                              │
│     $env = parse_ini_file($envFile);                      │
│     foreach ($env as $key => $value) {                    │
│         define($key, $value);    ← Define constantes     │
│     }                                                      │
│ }                                                          │
└────────────────┬────────────────────────────────────────────┘
                 │ Ahora están disponibles como constantes:
                 │ DB_HOST, DB_NAME, DB_USER, DB_PASS
                 ▼
┌─────────────────────────────────────────────────────────────┐
│ /app/Core/DB.php                                            │
│                                                             │
│ $dsn = sprintf(                                            │
│     "mysql:host=%s;port=%s;dbname=%s",                   │
│     DB_HOST,      ← mysql.c99.onrender.com               │
│     DB_PORT,      ← 3306                                  │
│     DB_NAME       ← restaurant_crm                        │
│ );                                                         │
│                                                             │
│ $pdo = new PDO($dsn, DB_USER, DB_PASS);  ← Login        │
└─────────────────────────────────────────────────────────────┘
```

---

## Request Routing: /login

```
Usuario escribe: https://restaurant-crm.onrender.com/login
                              │
                              ▼
Render reverse proxy (HTTPS → HTTP:8080, agrega headers)
                              │
                              ▼
Apache escucha :8080, DocumentRoot: /var/www/html/public
Archivo solicitado: /login (no existe como archivo)
                              │
                              ▼
.htaccess regla:
RewriteCond %{REQUEST_FILENAME} !-f  (no es archivo)
RewriteCond %{REQUEST_FILENAME} !-d  (no es directorio)
RewriteRule ^(.+)$ /index.php [QSA,L]
                              │
                              ▼
Apache redirige internamente a: /index.php
REQUEST_URI = /login  (se preserva)
                              │
                              ▼
index.php ejecuta:
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
// $url = '/login'
                              │
                              ▼
new App\Core\Router()
->handle('/login')            │
                              │
                              ▼
Router.php method: matchRoute('/login', 'GET')
Busca en rutas registradas:
$this->get('/login', 'AuthController@index')
                              │
                              ▼
Encontrado! Ejecuta:
new App\Controllers\AuthController()
->index()
                              │
                              ▼
AuthController retorna View: 'auth/index.php'
                              │
                              ▼
HTML enviado a usuario
✅ 200 OK (no 404)
```

---

## Flujo de Seguridad: HTTPS Detection

```
┌─────────────────────────────────────┐
│ Render Edge (Reverse Proxy)         │
│ Recibe: HTTPS request               │
│ Termina HTTPS automáticamente       │
│ Redirecciona intern a: HTTP:8080    │
│                                     │
│ Agrega header:                      │
│ X-Forwarded-Proto: https            │
│ X-Forwarded-For: 203.0.113.1        │
│ X-Real-IP: 203.0.113.1              │
└────────────────┬────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ Apache (nuestro contenedor)         │
│ Lee el header X-Forwarded-Proto     │
│                                     │
│ (Gracias a: SetEnvIf ... en Dockerfile)
│                                     │
│ Establece: $_SERVER['HTTPS'] = on   │
└────────────────┬────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ public/index.php                    │
│                                     │
│ $protocol = (                       │
│   !empty($_SERVER['HTTPS']) &&      │
│   $_SERVER['HTTPS'] !== 'off'       │
│ ) ? 'https' : 'http';               │
│                                     │
│ // $protocol = 'https' ✓            │
│                                     │
│ define('BASE_URL', $protocol .      │
│   '://' . $_SERVER['HTTP_HOST'] ./) │
│                                     │
│ // BASE_URL = https://...           │
└────────────────┬────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ Sessions: Seguridad mejorada        │
│                                     │
│ session_set_cookie_params([         │
│   'secure' => true,     ✓           │
│   'httponly' => true,   ✓           │
│   'samesite' => 'Lax'   ✓           │
│ ]);                                 │
│                                     │
│ (solo en HTTPS)                    │
└─────────────────────────────────────┘
```

---

## Componentes Críticos

### 1. Dockerfile
```
INPUT: Código local (/var/www/html)
↓
PROCESS:
  • Instala: php:8.2-apache
  • Extensiones: pdo_mysql
  • Módulos: rewrite, headers, remoteip
  • Config: DocumentRoot=/public, Listen=8080
  • Health check: curl http://localhost:8080/
↓
OUTPUT: Imagen Docker lista para Render
```

### 2. docker-entrypoint.sh
```
EJECUTA AL INICIAR CONTENEDOR:
  1. Lee env vars: DB_HOST, DB_USERNAME, etc.
  2. Genera: /var/www/html/.env
  3. Inicia: Apache
```

### 3. public/.htaccess
```
PROCESA CADA REQUEST:
  • Si no es archivo real (-f): rewrite a /index.php
  • Si no es directorio real (-d): rewrite a /index.php
  • Bloquea acceso: /app, /database, /vendor
  • Bloquea archivos: .env, .sql, .json
```

### 4. public/index.php
```
PUNTO DE ENTRADA:
  1. Define constantes de rutas
  2. Carga archivo .env
  3. Configura sesiones seguras
  4. Autoload de clases
  5. Inicializa Router
  6. Procesa REQUEST_URI
  7. Ejecuta controlador
```

---

## Monitoreo en Producción

```
Render Dashboard > Logs (actualiza en real-time)
                         │
                         ▼
           Busca indicadores de salud:
           
✓ BIEN                      ✗ PROBLEMA
─────────────────────────────────────────
√ ".env generado"           × "Connection refused"
√ "Apache" started          × "Fatal error"
sqrt "HTTP 200"             × "Segmentation fault"
√ Logs normal               × "out of memory"
√ DB conecta                × "Permission denied"
```

---

## Resumen en Números

```
┌────────────────────────────────┐
│ COMPONENTES                    │
├────────────────────────────────┤
│ Lineas Dockerfile: ~35         │
│ Lineas docker-entrypoint: ~25  │
│ Documentos guía: 10            │
│ Tiempo deployment: 25 min      │
│ Uptime esperado: 99.9%         │
│ Costo Render (Free): $0        │
└────────────────────────────────┘
```

---

## Diagrama Simplificado

```
┌─────────────────────────────────────────────────────────────┐
│                    USUARIO EN INTERNET                      │
│                  https://example.onrender.com               │
└──────────────────────────────┬──────────────────────────────┘
                               │
                    ┌──────────▼───────────┐
                    │   RENDER EDGE       │
                    │  (HTTPS → HTTP:8080)│
                    └──────────┬───────────┘
                               │
            ┌──────────────────┼──────────────────┐
            │                  │                  │
            ▼                  ▼                  ▼
┌──────────────────┐  ┌──────────────────┐  
│ CONTENEDOR DOCKER│  │  MYSQL DATABASE  │
│ (PHP + Apache)   │──▶│  (Render DB)     │
│                  │  │                  │
│ • .env (vars)    │  │ • users          │
│ • index.php      │  │ • restaurants    │
│ • app/           │  │ • menus          │
│ • public/.htaccess   │ • leads          │
│                  │  │                  │
└──────────────────┘  └──────────────────┘
```

---

## Checklist de Componentes

- [x] **Dockerfile**: Optimizado para Render (8080, mod_rewrite)
- [x] **docker-entrypoint.sh**: Genera .env automáticamente
- [x] **.htaccess**: Redirige todo a index.php
- [x] **index.php**: Carga .env y detecta HTTPS
- [x] **Router**: Mapea URLs a controladores  
- [x] **Models**: Usan constantes DB_*
- [x] **Variables de entorno**: Manejadas correctamente
- [x] **Documentación**: Completa y clara

---

**ARQUITECTURA: ✅ LISTA PARA PRODUCCIÓN**
