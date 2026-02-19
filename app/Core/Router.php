<?php

namespace App\Core;

/**
 * Sistema de enrutamiento simple
 */
class Router
{
    private array $routes = [];
    private string $controllerNamespace = 'App\Controllers\\';

    public function __construct()
    {
        // Registrar rutas por defecto
        $this->registerDefaultRoutes();
    }

    /**
     * Registrar rutas por defecto
     */
    private function registerDefaultRoutes(): void
    {
        // Debug & Test (remover en producción)
        if (getenv('APP_DEBUG') || !defined('APP_ENV') || APP_ENV !== 'production') {
            $this->get('/debug/csrf', 'DebugController@csrf');
            $this->post('/debug/csrf', 'DebugController@csrf');
            $this->get('/test/session', 'TestController@session');
            $this->get('/test/session-check', 'TestController@sessionCheck');
        }

        // Auth
        $this->get('/', 'AuthController@index');
        $this->get('/login', 'AuthController@index');
        $this->post('/login', 'AuthController@login');
        $this->get('/logout', 'AuthController@logout');

        // Dashboard
        $this->get('/dashboard', 'DashboardController@index');

        // Restaurants
        $this->get('/restaurants', 'RestaurantController@index');
        $this->get('/restaurants/create', 'RestaurantController@create');
        $this->post('/restaurants/store', 'RestaurantController@store');
        $this->get('/restaurants/edit/:id', 'RestaurantController@edit');
        $this->post('/restaurants/update/:id', 'RestaurantController@update');
        $this->post('/restaurants/delete/:id', 'RestaurantController@delete');
        $this->post('/restaurants/search', 'RestaurantController@search');

        // Leads
        $this->get('/leads', 'LeadController@index');
        $this->get('/leads/create', 'LeadController@create');
        $this->post('/leads/store', 'LeadController@store');
        $this->get('/leads/edit/:id', 'LeadController@edit');
        $this->post('/leads/update/:id', 'LeadController@update');
        $this->post('/leads/delete/:id', 'LeadController@delete');
        $this->post('/leads/search', 'LeadController@search');
        $this->post('/leads/search-restaurants', 'LeadController@searchRestaurants');

        // Categories
        $this->get('/categories/:restaurant_id', 'CategoryController@index');
        $this->post('/categories/store', 'CategoryController@store');
        $this->post('/categories/update/:id', 'CategoryController@update');
        $this->post('/categories/delete/:id', 'CategoryController@delete');

        // Menu
        $this->get('/menu/:restaurant_id', 'MenuController@index');
        $this->get('/menu/:restaurant_id/create', 'MenuController@create');
        $this->post('/menu/:restaurant_id/store', 'MenuController@store');
        $this->get('/menu/:restaurant_id/edit/:id', 'MenuController@edit');
        $this->post('/menu/:restaurant_id/update/:id', 'MenuController@update');
        $this->post('/menu/:restaurant_id/delete/:id', 'MenuController@delete');
    }

    /**
     * Registrar ruta GET
     */
    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    /**
     * Registrar ruta POST
     */
    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    /**
     * Manejar solicitud HTTP
     */
    public function handle(string $url): void
    {
        // Limpiar URL: remover query string, trim slashes, normalizar
        $url = parse_url($url, PHP_URL_PATH) ?? $url;
        $url = preg_replace('#/+#', '/', $url); // Eliminar múltiples slashes
        $url = trim($url, '/');
        if ($url === '') {
            $url = '/';
        } else {
            $url = '/' . $url;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $action = $this->matchRoute($url, $method);

        if (!$action) {
            http_response_code(404);
            echo '<h1>404 - Página no encontrada</h1>';
            echo '<p><strong>Ruta solicitada:</strong> ' . htmlspecialchars($url) . '</p>';
            echo '<p><strong>Método:</strong> ' . htmlspecialchars($method) . '</p>';
            echo '<hr>';
            echo '<h3>Rutas disponibles para ' . $method . ':</h3>';
            $routes = $this->routes[$method] ?? [];
            if (!empty($routes)) {
                echo '<ul>';
                foreach (array_keys($routes) as $route) {
                    echo '<li><code>' . htmlspecialchars($route) . '</code></li>';
                }
                echo '</ul>';
            } else {
                echo '<p style="color: red;">No hay rutas registradas para <strong>' . $method . '</strong></p>';
            }
            exit();
        }

        $this->executeAction($action);
    }

    /**
     * Buscar ruta coincidente
     */
    private function matchRoute(string $url, string $method): ?string
    {
        $routes = $this->routes[$method] ?? [];

        // Debug: mostrar ruta actual (opcional, comentar en producción)
        // error_log("Router Debug - URL: $url | Method: $method | Available routes: " . json_encode(array_keys($routes)));

        foreach ($routes as $path => $action) {
            $pattern = $this->pathToRegex($path);
            if (preg_match($pattern, $url, $matches)) {
                // Guardar parámetros de ruta extraídos
                $GLOBALS['route_params'] = array_slice($matches, 1);
                return $action;
            }
        }

        return null;
    }

    /**
     * Convertir ruta a expresión regular
     * Ejemplo: /menu/:restaurant_id/edit/:id -> #^/menu/([a-zA-Z0-9_-]+)/edit/([a-zA-Z0-9_-]+)$#
     */
    private function pathToRegex(string $path): string
    {
        // Escapar caracteres especiales excepto ':'
        $path = preg_quote($path, '#');
        // Revertir el escape de ':' para procesarlo
        $path = str_replace('\\:', ':', $path);
        // Convertir :parametro a captura de grupo
        $path = preg_replace('/:[a-zA-Z_]\w*/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $path . '(?:\?|$)#';
    }

    /**
     * Ejecutar acción del controlador
     */
    private function executeAction(string $action): void
    {
        list($controller, $method) = explode('@', $action);

        $controllerClass = $this->controllerNamespace . $controller;

        if (!class_exists($controllerClass)) {
            die("Controlador no encontrado: $controllerClass");
        }

        $instance = new $controllerClass();

        if (!method_exists($instance, $method)) {
            die("Método no encontrado: $method en $controllerClass");
        }

        call_user_func_array([$instance, $method], []);
    }

    /**
     * Obtener parámetros de ruta
     */
    public static function getParams(): array
    {
        return $GLOBALS['route_params'] ?? [];
    }

    /**
     * Obtener parámetro de ruta específico
     */
    public static function getParam(int $index): ?string
    {
        $params = self::getParams();
        return $params[$index] ?? null;
    }
}
?>
