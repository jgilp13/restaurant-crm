<?php

namespace App\Models;

use App\Core\DB;

class Category
{
    private static string $table = 'categories';

    /**
     * Obtener todas
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
     * Obtener categorías de un restaurante
     */
    public static function getByRestaurant(int $restaurantId): array
    {
        return DB::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE restaurant_id = ? ORDER BY name ASC",
            [$restaurantId]
        );
    }

    /**
     * Crear categoría
     */
    public static function create(array $data): int
    {
        $insertData = [
            'restaurant_id' => $data['restaurant_id'] ?? 0,
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? ''
        ];
        
        return DB::insert(self::$table, $insertData);
    }

    /**
     * Actualizar categoría
     */
    public static function update(int $id, array $data): int
    {
        $updateData = [
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? ''
        ];
        
        return DB::update(self::$table, $updateData, "id = ?", [$id]);
    }

    /**
     * Eliminar categoría
     */
    public static function delete(int $id): int
    {
        return DB::delete(self::$table, "id = ?", [$id]);
    }

    /**
     * Verificar si el nombre es único en el restaurante
     */
    public static function isNameUnique(string $name, int $restaurantId, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM " . self::$table . 
               " WHERE restaurant_id = ? AND name = ?";
        $params = [$restaurantId, $name];

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = DB::fetchOne($sql, $params);
        return ($result['total'] ?? 0) === 0;
    }

    /**
     * Validar datos de formulario
     */
    public static function validate(array $data, ?int $categoryId = null): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'El nombre es requerido';
        } elseif (!self::isNameUnique($data['name'], (int)($data['restaurant_id'] ?? 0), $categoryId)) {
            $errors['name'] = 'El nombre ya existe en este restaurante';
        }

        if (empty((int)($data['restaurant_id'] ?? 0))) {
            $errors['restaurant_id'] = 'El restaurante es requerido';
        }

        return $errors;
    }

    /**
     * Contar total de categorías
     */
    public static function count(): int
    {
        return DB::count(self::$table);
    }

    /**
     * Últimas categorías
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
