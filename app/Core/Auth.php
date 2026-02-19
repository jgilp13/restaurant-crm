<?php

namespace App\Core;

/**
 * Gestión de autenticación y sesiones
 */
class Auth
{
    private const SESSION_KEY = 'user_id';
    private const ADMIN_EMAIL = 'admin@restaurant.crm';
    private const ADMIN_PASSWORD = 'admin123'; // En producción usar hash

    /**
     * Intentar login
     */
    public function login(string $email, string $password): bool
    {
        // Validación básica (en producción buscar en BD)
        if ($email === self::ADMIN_EMAIL && $password === self::ADMIN_PASSWORD) {
            session_regenerate_id(true);
            $_SESSION[self::SESSION_KEY] = 1;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'admin';
            return true;
        }

        return false;
    }

    /**
     * Verificar si está autenticado
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /**
     * Requerir login (redirige si no está autenticado)
     */
    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: /');
            exit();
        }
    }

    /**
     * Obtener ID de usuario actual
     */
    public function getUserId(): ?int
    {
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    /**
     * Obtener email de usuario actual
     */
    public function getUserEmail(): ?string
    {
        return $_SESSION['user_email'] ?? null;
    }

    /**
     * Obtener rol de usuario actual
     */
    public function getUserRole(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        session_destroy();
    }

    /**
     * Verificar si es admin
     */
    public function isAdmin(): bool
    {
        return $this->getUserRole() === 'admin';
    }
}
?>
