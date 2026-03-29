<?php
declare(strict_types=1);

final class Administradora
{
    public static function active(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM administradoras WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM administradoras ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM administradoras WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO administradoras (name, type, contact_name, phone, email, is_active, sort_order) VALUES (:name, :type, :contact_name, :phone, :email, :is_active, :sort_order)');
        $stmt->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'contact_name' => $data['contact_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare('UPDATE administradoras SET name = :name, type = :type, contact_name = :contact_name, phone = :phone, email = :email, is_active = :is_active, sort_order = :sort_order WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['type'],
            'contact_name' => $data['contact_name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM administradoras WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
