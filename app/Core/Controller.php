<?php

namespace App\Core;

/**
 * Controlador base para toda la aplicación
 */
class Controller
{
    protected View $view;
    protected Auth $auth;

    public function __construct()
    {
        $this->view = new View();
        $this->auth = new Auth();

        // Verifica si la ruta requiere autenticación
        if (!$this->isPublicRoute()) {
            $this->auth->requireLogin();
        }
    }

    /**
     * Determinar si la ruta es pública
     */
    protected function isPublicRoute(): bool
    {
        $publicRoutes = [
            '/',
            '/login',
            '/logout',
        ];

        $url = trim($_SERVER['REQUEST_URI'], '/');
        $url = str_replace('restaurant-crm/', '', $url);

        foreach ($publicRoutes as $route) {
            if (strpos('/' . $url, $route) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renderizar vista
     */
    protected function render(string $view, array $data = []): void
    {
        $this->view->render($view, $data);
    }

    /**
     * Redireccionar
     */
    protected function redirect(string $url): void
    {
        $url = '/' . ltrim($url, '/');
        header('Location: ' . $url);
        exit();
    }

    /**
     * Establecer mensaje flash
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type, // 'success', 'error', 'warning', 'info'
            'message' => $message
        ];
    }

    /**
     * Obtener mensaje flash
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Enviar respuesta JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }

    /**
     * Validar datos POST
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            $rules_array = explode('|', $fieldRules);

            foreach ($rules_array as $rule) {
                $this->validateField($field, $value, $rule, $errors);
            }
        }

        return $errors;
    }

    /**
     * Validar un campo específico
     */
    private function validateField(string $field, mixed $value, string $rule, array &$errors): void
    {
        if (strpos($rule, ':') !== false) {
            [$rule, $param] = explode(':', $rule);
        }

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $errors[$field] = ucfirst($field) . ' es requerido';
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Email inválido';
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < (int)$param) {
                    $errors[$field] = ucfirst($field) . ' debe tener mínimo ' . $param . ' caracteres';
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > (int)$param) {
                    $errors[$field] = ucfirst($field) . ' debe tener máximo ' . $param . ' caracteres';
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $errors[$field] = ucfirst($field) . ' debe ser numérico';
                }
                break;

            case 'tel':
                if (!empty($value) && !preg_match('/^[0-9+\-\s\(\)\.]+$/', $value)) {
                    $errors[$field] = 'Teléfono inválido';
                }
                break;
        }
    }
}
?>
