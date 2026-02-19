<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Clase para gestión de conexión a base de datos con PDO
 */
class DB
{
    private static ?PDO $connection = null;
    private static array $config = [];

    /**
     * Inicializar configuración de base de datos
     */
    public static function config(array $dbConfig): void
    {
        self::$config = $dbConfig;
    }

    /**
     * Obtener conexión a base de datos
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::connect();
        }

        return self::$connection;
    }

    /**
     * Establecer conexión con la base de datos
     */
    private static function connect(): void
    {
        try {
            $host = self::$config['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
            $port = self::$config['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';
            $db = self::$config['DB_NAME'] ?? getenv('DB_NAME') ?: 'restaurant_crm';
            $user = self::$config['DB_USER'] ?? getenv('DB_USER') ?: 'root';
            $pass = self::$config['DB_PASS'] ?? getenv('DB_PASS') ?: '';
            $charset = 'utf8mb4';

            // Soporte para host:port en una sola variable (Render.com)
            if (strpos($host, ':') !== false) {
                list($host, $port) = explode(':', $host);
            }

            // Construir DSN con soporte para TCP/IP
            // Usar "localhost" sin puerto para socket Unix, usar host:port para TCP
            $dsn = ($port !== '3306' || (string)$host !== 'localhost') 
                ? "mysql:host=$host;port=$port;dbname=$db;charset=$charset"
                : "mysql:host=$host;dbname=$db;charset=$charset";

            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false,
            ]);

        } catch (PDOException $e) {
            die('Error de conexión a base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar query preparada
     */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $connection = self::getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtener un registro
     */
    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtener múltiples registros
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Insertar registro
     */
    public static function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        self::query($sql, array_values($data));

        return self::getConnection()->lastInsertId();
    }

    /**
     * Actualizar registros
     */
    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $sets = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $table SET $sets WHERE $where";

        $allParams = array_merge(array_values($data), $params);
        $stmt = self::query($sql, $allParams);

        return $stmt->rowCount();
    }

    /**
     * Eliminar registros
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Obtener cantidad de registros
     */
    public static function count(string $table, string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $result = self::fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Ejecutar transacción
     */
    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public static function commit(): void
    {
        self::getConnection()->commit();
    }

    /**
     * Revertir transacción
     */
    public static function rollback(): void
    {
        self::getConnection()->rollback();
    }
}
?>
