<?php

namespace App\Models;

use App\Core\DB;

class MenuItem
{
    private static string $table = 'menu_items';

    /**
     * Crear item de menú
     */
    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return DB::insert(self::$table, $data);
    }

    /**
     * Buscar por ID
     */
    public static function findById(int $id): ?array
    {
        return DB::fetchOne("SELECT * FROM " . self::$table . " WHERE id = ?", [$id]);
    }

    /**
     * Obtener items de un restaurante con paginación
     */
    public static function filterByRestaurant(int $restaurantId, int $page, int $perPage): array
    {
        $offset = (int)(($page - 1) * $perPage);
        $perPage = (int)$perPage;
        
        return DB::fetchAll(
            "SELECT * FROM " . self::$table . " 
             WHERE restaurant_id = ? 
             ORDER BY id DESC LIMIT " . $perPage . " OFFSET " . $offset,
            [$restaurantId]
        );
    }

    /**
     * Contar items de un restaurante
     */
    public static function countByRestaurant(int $restaurantId): int
    {
        return DB::count(self::$table, "restaurant_id = ?", [$restaurantId]);
    }

    /**
     * Obtener items por categoría
     */
    public static function filterByCategory(int $restaurantId, int $categoryId, int $page, int $perPage): array
    {
        $offset = (int)(($page - 1) * $perPage);
        $perPage = (int)$perPage;
        
        return DB::fetchAll(
            "SELECT * FROM " . self::$table . " 
             WHERE restaurant_id = ? AND category_id = ? 
             ORDER BY id DESC LIMIT " . $perPage . " OFFSET " . $offset,
            [$restaurantId, $categoryId]
        );
    }

    /**
     * Contar items por categoría
     */
    public static function countByCategory(int $restaurantId, int $categoryId): int
    {
        return DB::count(
            self::$table,
            "restaurant_id = ? AND category_id = ?",
            [$restaurantId, $categoryId]
        );
    }

    /**
     * Obtener todos
     */
    public static function all(): array
    {
        return DB::fetchAll("SELECT * FROM " . self::$table . " ORDER BY id DESC");
    }

    /**
     * Actualizar
     */
    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return DB::update(self::$table, $data, "id = ?", [$id]);
    }

    /**
     * Eliminar
     */
    public static function delete(int $id): int
    {
        return DB::delete(self::$table, "id = ?", [$id]);
    }

    /**
     * Contar total
     */
    public static function count(): int
    {
        return DB::count(self::$table);
    }

    /**
     * Últimos items
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
