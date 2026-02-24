<?php
/**
 * Restaurant CRM - Front Controller
 * Punto de entrada único para toda la aplicación
 */

// 🔴 CRÍTICO: Charset UTF-8 (debe ser lo primero antes que todo)
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Definir constantes de rutas
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', APP_PATH . '/Core');
define('CONTROLLER_PATH', APP_PATH . '/Controllers');
define('MODEL_PATH', APP_PATH . '/Models');
define('VIEW_PATH', APP_PATH . '/Views');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// URL base dinámica según el servidor
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
// Si scriptPath es "/" evitar doble slash
$basePath = ($scriptPath === '/' || $scriptPath === '') ? '' : rtrim($scriptPath, '/');
define('BASE_URL', $protocol . '://' . $host . $basePath . '/');

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de sesión segura
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Iniciar sesión
session_start();

// Cargar archivo de configuración .env
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    foreach ($env as $key => $value) {
        define($key, $value);
    }
}

// Autoloader simple
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    // Convertir la primera parte a minúsculas para que coincida con la carpeta 'app'
    $parts[0] = strtolower($parts[0]);
    $file = ROOT_PATH . '/' . implode('/', $parts) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Cargar núcleo de la aplicación
require_once CORE_PATH . '/DB.php';
require_once CORE_PATH . '/Router.php';
require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/View.php';
require_once CORE_PATH . '/Auth.php';
require_once CORE_PATH . '/Csrf.php';

// Iniciar enrutador
$router = new App\Core\Router();

// Obtener URL desde REQUEST_URI
// IMPORTANTE: normalizar múltiples slashes PRIMERO (antes de parse_url)
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Normalizar múltiples slashes: //login -> /login
$requestUri = preg_replace('#/+#', '/', $requestUri);

// Extraer ruta sin query string
$url = parse_url($requestUri, PHP_URL_PATH) ?? '/';

// Asegurar formato correcto de URL
$url = '/' . trim($url, '/');

// Redirigir el dominio raíz a la landing de demo
if ($url === '/') {
    header('Location: /landing.html', true, 302);
    exit;
}

// Ejecutar aplicación
$router->handle($url);
?>
