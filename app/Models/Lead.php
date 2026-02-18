<?php

namespace App\Models;

use App\Core\DB;

class Lead
{
    private static string $table = 'leads';

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
     * Crear lead
     */
    public static function create(array $data): int
    {
        $insertData = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'restaurant_name' => $data['restaurant_name'] ?? '',
            'status' => $data['status'] ?? 'new',
            'notes' => $data['notes'] ?? ''
        ];
        
        return DB::insert(self::$table, $insertData);
    }

    /**
     * Actualizar lead
     */
    public static function update(int $id, array $data): int
    {
        $updateData = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'restaurant_name' => $data['restaurant_name'] ?? '',
            'status' => $data['status'] ?? 'new',
            'notes' => $data['notes'] ?? ''
        ];
        
        return DB::update(self::$table, $updateData, "id = ?", [$id]);
    }

    /**
     * Eliminar lead
     */
    public static function delete(int $id): int
    {
        return DB::delete(self::$table, "id = ?", [$id]);
    }

    /**
     * Obtener con filtros
     */
    public static function getFiltered(array $filters = []): array
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $sql .= " AND (name LIKE ? OR email LIKE ? OR restaurant_name LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY created_at DESC";

        return DB::fetchAll($sql, $params);
    }

    /**
     * Obtener por estado
     */
    public static function getByStatus(string $status): array
    {
        return DB::fetchAll(
            "SELECT * FROM " . self::$table . " WHERE status = ? ORDER BY created_at DESC",
            [$status]
        );
    }

    /**
     * Búsqueda
     */
    public static function search(string $query): array
    {
        $query = "%$query%";
        return DB::fetchAll(
            "SELECT * FROM " . self::$table . " 
             WHERE name LIKE ? OR email LIKE ? OR restaurant_name LIKE ? 
             ORDER BY created_at DESC",
            [$query, $query, $query]
        );
    }

    /**
     * Verificar si email existe (excluyendo un lead si se proporciona ID)
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

        if (empty(trim($data['restaurant_name'] ?? ''))) {
            $errors['restaurant_name'] = 'El nombre del restaurante es requerido';
        } else {
            // Verificar que el restaurante existe
            $restaurant = DB::fetchOne(
                "SELECT id FROM restaurants WHERE name = ?",
                [$data['restaurant_name']]
            );
            if (!$restaurant) {
                $errors['restaurant_name'] = 'El restaurante no existe en el sistema';
            }
        }

        $validStatuses = ['new', 'contacted', 'interested', 'negotiating', 'closed', 'rejected'];
        if (!empty($data['status']) && !in_array($data['status'], $validStatuses)) {
            $errors['status'] = 'El estado no es válido';
        }

        return $errors;
    }

    /**
     * Obtener estados válidos
     */
    public static function getValidStatuses(): array
    {
        return ['new', 'contacted', 'interested', 'negotiating', 'closed', 'rejected'];
    }

    /**
     * Etiqueta legible para estado
     */
    public static function getStatusLabel(string $status): string
    {
        $labels = [
            'new' => 'Nuevo',
            'contacted' => 'Contactado',
            'interested' => 'Interesado',
            'negotiating' => 'Negociando',
            'closed' => 'Cerrado',
            'rejected' => 'Rechazado'
        ];
        return $labels[$status] ?? $status;
    }

    /**
     * Contar total de leads
     */
    public static function count(): int
    {
        $result = DB::fetchOne("SELECT COUNT(*) as total FROM " . self::$table);
        return $result['total'] ?? 0;
    }

    /**
     * Últimos leads
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
