<?php
declare(strict_types=1);

final class FormaEnvio
{
    public static function active(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM formas_envio WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM formas_envio ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO formas_envio (name, icon_class, color_hex, is_active, sort_order) VALUES (:name, :icon_class, :color_hex, :is_active, :sort_order)');
        $stmt->execute([
            'name' => $data['name'],
            'icon_class' => $data['icon_class'],
            'color_hex' => $data['color_hex'],
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        return Database::lastInsertId('formas_envio');
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare('UPDATE formas_envio SET name = :name, icon_class = :icon_class, color_hex = :color_hex, is_active = :is_active, sort_order = :sort_order WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'icon_class' => $data['icon_class'],
            'color_hex' => $data['color_hex'],
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM formas_envio WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
