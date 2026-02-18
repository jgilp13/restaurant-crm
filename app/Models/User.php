<?php

namespace App\Models;

use App\Core\DB;

class User
{
    private static string $table = 'users';

    /**
     * Crear usuario
     */
    public static function create(array $data): int
    {
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
     * Buscar por email
     */
    public static function findByEmail(string $email): ?array
    {
        return DB::fetchOne("SELECT * FROM " . self::$table . " WHERE email = ?", [$email]);
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
}
?>
