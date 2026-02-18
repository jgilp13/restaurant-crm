# Restaurant CRM

Sistema profesional de gestión y CRM para restaurantes. Desarrollado con PHP 8.1+, MySQL y arquitectura MVC simple.

## Características

✅ **Gestión de Restaurantes**
- CRUD completo (Crear, Leer, Actualizar, Eliminar)
- Búsqueda y filtrado
- Paginación

✅ **Gestión de Leads**
- Filtro por estado (Nuevo, Contactado, Interesado, Negociando, Cerrado, Rechazado)
- Seguimiento de prospectos
- Notas y observaciones

✅ **Gestión de Menú**
- CRUD de categorías de menú
- CRUD de items de menú por restaurante
- Precios y descripciones
- Filtrado por categoría

✅ **Características Técnicas**
- Autenticación básica
- PDO con prepared statements
- Protección CSRF
- Validación backend
- Mensajes flash (éxito/error)
- Layout responsivo con Bootstrap 5
- Base de datos relacional

## Requisitos del Sistema

- PHP 8.1 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado
- XAMPP (recomendado para desarrollo)

## Instalación Rápida

### 1. Descargar el Proyecto

```bash
# El proyecto ya debe estar en:
c:\xampp\htdocs\restaurant-crm\
```

### 2. Crear Base de Datos

Abre phpMyAdmin (http://localhost/phpmyadmin) y:

1. Crea una base de datos llamada `restaurant_crm`
2. Ve a la pestaña SQL
3. Copia y pega el contenido de `database/schema.sql`
4. Ejecuta la consulta
5. (Opcional) Repite con `database/seed.sql` para datos de prueba

O desde línea de comandos:

```bash
mysql -u root -p restaurant_crm < database/schema.sql
mysql -u root -p restaurant_crm < database/seed.sql
```

### 3. Configurar Variables de Entorno

Edita `.env` en la raíz del proyecto:

```env
DB_HOST=localhost
DB_NAME=restaurant_crm
DB_USER=root
DB_PASS=

APP_NAME=Restaurant CRM
APP_ENV=development
APP_DEBUG=true

ITEMS_PER_PAGE=10
```

### 4. Acceder a la Aplicación

Abre tu navegador y ve a:

```
http://localhost/restaurant-crm/
```

### 5. Credenciales de Acceso (Demo)

```
Email: admin@restaurant.crm
Contraseña: admin123
```

## Estructura del Proyecto

```
restaurant-crm/
├── public/
│   ├── index.php          # Front Controller
│   ├── .htaccess          # URL Rewriting
│   └── assets/            # CSS, JS, imágenes
├── app/
│   ├── Core/
│   │   ├── DB.php         # Gestión de BD con PDO
│   │   ├── Router.php     # Enrutamiento
│   │   ├── Controller.php # Controlador base
│   │   ├── View.php       # Motor de vistas
│   │   ├── Auth.php       # Autenticación
│   │   └── Csrf.php       # Protección CSRF
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── RestaurantController.php
│   │   ├── LeadController.php
│   │   └── MenuController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Restaurant.php
│   │   ├── Lead.php
│   │   ├── Category.php
│   │   └── MenuItem.php
│   └── Views/
│       ├── layouts/
│       ├── auth/
│       ├── dashboard/
│       ├── restaurants/
│       ├── leads/
│       └── menu/
├── database/
│   ├── schema.sql         # Estructura de BD
│   └── seed.sql           # Datos de prueba
└── .env                   # Configuración

```

## Rutas Disponibles

### Autenticación
- `GET /` - Login
- `POST /login` - Procesar login
- `GET /logout` - Cerrar sesión

### Dashboard
- `GET /dashboard` - Panel de control

### Restaurantes
- `GET /restaurants` - Listar restaurantes
- `GET /restaurants/create` - Formulario crear
- `POST /restaurants/store` - Guardar restaurante
- `GET /restaurants/edit/:id` - Formulario editar
- `POST /restaurants/update/:id` - Actualizar usuario
- `POST /restaurants/delete/:id` - Eliminar restaurante

### Leads
- `GET /leads` - Listar leads
- `GET /leads?status=new` - Filtrar por estado
- `GET /leads/create` - Formulario crear
- `POST /leads/store` - Guardar lead
- `GET /leads/edit/:id` - Formulario editar
- `POST /leads/update/:id` - Actualizar lead
- `POST /leads/delete/:id` - Eliminar lead

### Menú
- `GET /menu/:restaurant_id` - Listar menú
- `GET /menu/:restaurant_id/create` - Formulario crear platillo
- `POST /menu/:restaurant_id/store` - Guardar platillo
- `GET /menu/:restaurant_id/edit/:id` - Formulario editar
- `POST /menu/:restaurant_id/update/:id` - Actualizar platillo
- `POST /menu/:restaurant_id/delete/:id` - Eliminar platillo

## Características de Seguridad

✅ **PDO Prepared Statements** - Previene SQL Injection
✅ **CSRF Tokens** - Protección contra ataques CSRF
✅ **XSS Prevention** - Escapado de salida HTML
✅ **Autenticación** - Sistemas de sesiones
✅ **Validación Backend** - Validación de todos los datos

## Validación de Campos

Todos los formularios incluyen validación:
- `required` - Campo obligatorio
- `email` - Formato email válido
- `min:n` - Mínimo n caracteres
- `max:n` - Máximo n caracteres
- `numeric` - Solo números
- `tel` - Formato teléfono válido

## Paginación

Todos los listados incluyen paginación con:
- Items por página configurable
- Navegación intuitiva
- Primera/Última página
- Anterior/Siguiente

## Errores Comunes

### Error: "Base de datos no encontrada"
- Verifica que creaste la BD `restaurant_crm`
- Revisa las credenciales en `.env`

### Error: "_htaccess not working"
- Asegúrate que mod_rewrite está habilitado en Apache
- Reinicia Apache después de habilitarlo

### Error: "Archivo de vista no encontrado"
- Verifica que los archivos .php existan en `app/Views/`
- Revisa la ruta en las rutas del router

## Extensiones Futuras

🔄 Sistema de usuarios múltiples
🔄 Autenticación con contraseña hasheada
🔄 API REST
🔄 Sistema de gráficos
🔄 Reportes PDF
🔄 Exportación a Excel

## Soporte

Para problemas:
1. Verifica la consola del navegador (F12)
2. Revisa los logs de Apache
3. Comprueba la configuración de `.env`
4. Asegúrate de que PHP 8.1+ está activo

## Licencia

Código abierto para uso educativo y comercial.

---

**Restaurant CRM v1.0** | Desarrollado con ❤️ para freelancers
