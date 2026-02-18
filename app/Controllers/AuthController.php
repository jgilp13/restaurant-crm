<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function index(): void
    {
        // Si ya está logueado, redirige al dashboard
        $auth = new Auth();
        if ($auth->isLoggedIn()) {
            header('Location: /dashboard');
            exit();
        }

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->render('auth.index', [
            'flash' => $flash
        ]);
    }

    /**
     * Procesar login
     */
    public function login(): void
    {
        // Verificar CSRF
        if (!Csrf::verifyPost()) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Token CSRF inválido'
            ];
            header('Location: /');
            exit();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validar
        $errors = [];
        if (empty($email)) $errors['email'] = 'Email requerido';
        if (empty($password)) $errors['password'] = 'Contraseña requerida';

        if (!empty($errors)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Completa todos los campos'
            ];
            header('Location: /');
            exit();
        }

        $auth = new Auth();
        if ($auth->login($email, $password)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Bienvenido ' . $email
            ];
            header('Location: /dashboard');
            exit();
        }

        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Email o contraseña incorrectos'
        ];
        header('Location: /');
        exit();
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        $auth = new Auth();
        $auth->logout();

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Sesión cerrada exitosamente'
        ];

        header('Location: /');
        exit();
    }
}
?>
