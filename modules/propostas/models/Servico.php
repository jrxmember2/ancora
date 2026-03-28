<?php
declare(strict_types=1);

final class Servico
{
    public static function active(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM servicos WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM servicos ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO servicos (name, description, is_active, sort_order) VALUES (:name, :description, :is_active, :sort_order)');
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        return Database::lastInsertId('servicos');
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare('UPDATE servicos SET name = :name, description = :description, is_active = :is_active, sort_order = :sort_order WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM servicos WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
