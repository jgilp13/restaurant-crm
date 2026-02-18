<?php

namespace App\Core;

/**
 * Protección contra ataques CSRF
 */
class Csrf
{
    private const TOKEN_KEY = 'csrf_token';
    private const TOKEN_FIELD = 'csrf_token';
    private const TOKEN_TIME_LIMIT = 3600; // 1 hora

    /**
     * Generar token CSRF
     */
    public static function generateToken(): string
    {
        if (empty($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = [
                'token' => bin2hex(random_bytes(32)),
                'time' => time()
            ];
        }

        return $_SESSION[self::TOKEN_KEY]['token'];
    }

    /**
     * Obtener token actual
     */
    public static function getToken(): string
    {
        return self::generateToken();
    }

    /**
     * Verificar si token es válido
     */
    public static function verify(string $token): bool
    {
        if (empty($_SESSION[self::TOKEN_KEY])) {
            return false;
        }

        $storedToken = $_SESSION[self::TOKEN_KEY]['token'];
        $storedTime = $_SESSION[self::TOKEN_KEY]['time'];
        $currentTime = time();

        // Verificar token
        if (!hash_equals($storedToken, $token)) {
            return false;
        }

        // Verificar expiración
        if (($currentTime - $storedTime) > self::TOKEN_TIME_LIMIT) {
            return false;
        }

        // Regenerar token después de verificación
        unset($_SESSION[self::TOKEN_KEY]);

        return true;
    }

    /**
     * Validar token POST
     */
    public static function verifyPost(): bool
    {
        $token = $_POST[self::TOKEN_FIELD] ?? '';
        return self::verify($token);
    }

    /**
     * Campo input oculto con token
     */
    public static function field(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::TOKEN_FIELD . '" value="' . $token . '">';
    }

    /**
     * Input token para formularios
     */
    public static function input(): string
    {
        return self::field();
    }

    /**
     * Token como atributo (para AJAX)
     */
    public static function token(): string
    {
        return self::getToken();
    }
}
?>
