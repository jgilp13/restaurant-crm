<?php

namespace App\Models;

use App\Core\DB;

class Restaurant
{
    private static string $table = 'restaurants';

    /**
     * Obtener todos
     */
    public static function all(): array
    {
        return DB::fetchAll("SELECT * FROM " . self::$table . " ORDER BY created_at DESC");
    }

    /**
     * Buscar por ID
     */
    public static function findById(int $id): ?array
    {
        return DB::fetchOne("SELECT * FROM " . self::$table . " WHERE id = ?", [$id]);
    }

    /**
     * Crear restaurante
     */
    public static function create(array $data): int
    {
        $insertData = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'description' => $data['description'] ?? ''
        ];
        
        return DB::insert(self::$table, $insertData);
    }

    /**
     * Actualizar restaurante
     */
    public static function update(int $id, array $data): int
    {
        $updateData = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'description' => $data['description'] ?? ''
        ];
        
        return DB::update(self::$table, $updateData, "id = ?", [$id]);
    }

    /**
     * Eliminar restaurante
     */
    public static function delete(int $id): int
    {
        return DB::delete(self::$table, "id = ?", [$id]);
    }

    /**
     * Buscar restaurantes
     */
    public static function search(string $query): array
    {
        $query = "%$query%";
        return DB::fetchAll(
            "SELECT * FROM " . self::$table . " 
             WHERE name LIKE ? OR email LIKE ? OR city LIKE ? 
             ORDER BY created_at DESC",
            [$query, $query, $query]
        );
    }

    /**
     * Etiqueta legible para estado
     */
    public static function getStatusLabel(string $status): string
    {
        $labels = [
            'active' => 'Activo',
            'inactive' => 'Inactivo'
        ];
        return $labels[$status] ?? $status;
    }

    /**
     * Verificar si email existe (excluyendo un restaurante si se proporciona ID)
     */
    public static function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE email = ?";
        $params = [$email];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DB::fetchOne($sql, $params);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Validar datos de formulario
     */
    public static function validate(array $data, ?int $excludeId = null): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'El nombre es requerido';
        }

        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'El email es requerido';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido';
        } elseif (self::emailExists($data['email'], $excludeId)) {
            $errors['email'] = 'Este email ya está registrado';
        }

        if (empty(trim($data['phone'] ?? ''))) {
            $errors['phone'] = 'El teléfono es requerido';
        }

        if (empty(trim($data['address'] ?? ''))) {
            $errors['address'] = 'La dirección es requerida';
        }

        if (empty(trim($data['city'] ?? ''))) {
            $errors['city'] = 'La ciudad es requerida';
        }

        return $errors;
    }

    /**
     * Contar total de restaurantes
     */
    public static function count(): int
    {
        $result = DB::fetchOne("SELECT COUNT(*) as total FROM " . self::$table);
        return $result['total'] ?? 0;
    }

    /**
     * Últimos restaurantes
     */
    public static function latest(int $limit = 5): array
    {
        $limit = (int)$limit;
        return DB::fetchAll(
            "SELECT * FROM " . self::$table . " ORDER BY created_at DESC LIMIT " . $limit
        );
    }
}
?>
