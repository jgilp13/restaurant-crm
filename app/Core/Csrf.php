<?php

namespace App\Core;

/**
 * Protección contra ataques CSRF - Implementación simplificada
 */
class Csrf
{
    private const TOKEN_NAME = '_csrf_token';
    private const TOKEN_TIME = '_csrf_time';

    /**
     * Generar o obtener token CSRF
     * Se genera UNA sola vez por sesión y se mantiene válido
     */
    public static function generateToken(): string
    {
        // Si el token ya existe y es válido, devolverlo
        if (self::tokenExists() && !self::isExpired()) {
            return $_SESSION[self::TOKEN_NAME];
        }

        // Generar nuevo token
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_NAME] = $token;
        $_SESSION[self::TOKEN_TIME] = time();

        return $token;
    }

    /**
     * Obtener token actual
     */
    public static function getToken(): string
    {
        return self::generateToken();
    }

    /**
     * Verificar si token existe en sesión
     */
    private static function tokenExists(): bool
    {
        return isset($_SESSION[self::TOKEN_NAME]) 
            && is_string($_SESSION[self::TOKEN_NAME])
            && !empty($_SESSION[self::TOKEN_NAME]);
    }

    /**
     * Verificar si token está expirado (1 hora)
     */
    private static function isExpired(): bool
    {
        if (!isset($_SESSION[self::TOKEN_TIME])) {
            return true;
        }

        $elapsed = time() - $_SESSION[self::TOKEN_TIME];
        return $elapsed > 3600;
    }

    /**
     * Verificar si token es válido
     */
    public static function verify(string $token): bool
    {
        // Token vacío = inválido
        if (empty($token)) {
            self::logDebug("Token vacío");
            return false;
        }

        // Sesión sin token = inválido
        if (!self::tokenExists()) {
            self::logDebug("Token no existe en sesión");
            return false;
        }

        // Token expirado = inválido
        if (self::isExpired()) {
            self::logDebug("Token expirado");
            return false;
        }

        // Comparación segura del token
        if (!hash_equals($_SESSION[self::TOKEN_NAME], $token)) {
            self::logDebug("Token no coincide");
            return false;
        }

        // ✅ Token válido - se mantiene en sesión para próximas solicitudes
        return true;
    }

    /**
     * Validar token POST
     */
    public static function verifyPost(): bool
    {
        $token = $_POST[self::TOKEN_NAME] ?? '';
        $result = self::verify($token);

        if (!$result) {
            self::logDebug("POST verification failed - Token: " . substr($token, 0, 20) . "...");
        }

        return $result;
    }

    /**
     * Campo input oculto para formularios
     */
    public static function field(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::TOKEN_NAME . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Alias de field()
     */
    public static function input(): string
    {
        return self::field();
    }

    /**
     * Obtener token como valor (para AJAX, data attributes, etc)
     */
    public static function token(): string
    {
        return self::getToken();
    }

    /**
     * Limpiar token (típicamente al logout)
     */
    public static function clear(): void
    {
        unset($_SESSION[self::TOKEN_NAME]);
        unset($_SESSION[self::TOKEN_TIME]);
    }

    /**
     * Regenerar token
     */
    public static function regenerate(): string
    {
        self::clear();
        return self::generateToken();
    }

    /**
     * Debug logging
     */
    private static function logDebug(string $message): void
    {
        $isDebug = getenv('APP_DEBUG') !== false 
            || (!defined('APP_ENV') || (defined('APP_ENV') && APP_ENV !== 'production'));

        if (!$isDebug) {
            return;
        }

        $debug_msg = "CSRF: " . $message;
        if (self::tokenExists()) {
            $debug_msg .= " | Session token: " . substr($_SESSION[self::TOKEN_NAME], 0, 16) . "...";
        }
        error_log($debug_msg);
    }
}
?>
