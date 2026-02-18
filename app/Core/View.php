<?php

namespace App\Core;

/**
 * Motor de vistas simple
 */
class View
{
    private string $viewPath;

    public function __construct()
    {
        $this->viewPath = VIEW_PATH;
    }

    /**
     * Renderizar vista con layout
     */
    public function render(string $view, array $data = []): void
    {
        $viewFile = $this->viewPath . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            die('Vista no encontrada: ' . $viewFile);
        }

        // Extraer variables para usarlas en la vista
        extract($data);

        // Iniciar buffer de salida
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Envolver con layout
        $this->renderLayout($content, $data);
    }

    /**
     * Renderizar layout con contenido
     */
    private function renderLayout(string $content, array $data = []): void
    {
        extract($data);

        $layoutFile = $this->viewPath . '/layouts/main.php';

        if (!file_exists($layoutFile)) {
            echo $content;
            return;
        }

        ob_start();
        require $layoutFile;
        echo ob_get_clean();
    }

    /**
     * Incluir parcial (subvista)
     */
    public static function partial(string $path, array $data = []): void
    {
        $viewPath = VIEW_PATH;
        $file = $viewPath . '/' . str_replace('.', '/', $path) . '.php';

        if (file_exists($file)) {
            extract($data);
            require $file;
        }
    }

    /**
     * Escapar string para prevenir XSS
     */
    public static function escape(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Obtener URL con base
     * Retorna URL relativa normalizada sin múltiples slashes
     */
    public static function url(string $path = ''): string
    {
        // Normalizar: eliminar múltiples slashes y asegurar resultado
        $path = '/' . ltrim($path, '/');
        $path = preg_replace('#/+#', '/', $path);
        
        // Retornar ruta relativa (más simple y robusta que URL absoluta)
        return $path;
    }

    /**
     * Obtener asset (CSS, JS, imágenes)
     */
    public static function asset(string $path): string
    {
        return BASE_URL . 'assets/' . ltrim($path, '/');
    }
}
?>
