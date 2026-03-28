<?php
declare(strict_types=1);

final class Module
{
    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM system_modules ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function enabled(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM system_modules WHERE is_enabled = 1 ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function isEnabled(string $slug): bool
    {
        $stmt = Database::connection()->prepare('SELECT is_enabled FROM system_modules WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $value = $stmt->fetchColumn();

        return (int) $value === 1;
    }

    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM system_modules WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public static function setStatus(int $id, bool $enabled): bool
    {
        $stmt = Database::connection()->prepare('UPDATE system_modules SET is_enabled = :enabled WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'enabled' => $enabled ? 1 : 0,
        ]);
    }

    public static function userHasAccess(int $userId, string $slug): bool
    {
        $stmt = Database::connection()->prepare('
            SELECT 1
            FROM user_module_permissions ump
            INNER JOIN system_modules sm ON sm.id = ump.module_id
            WHERE ump.user_id = :user_id
              AND sm.slug = :slug
            LIMIT 1
        ');
        $stmt->execute([
            'user_id' => $userId,
            'slug' => $slug,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public static function permissionsByUser(int $userId): array
    {
        $stmt = Database::connection()->prepare('
            SELECT sm.slug
            FROM user_module_permissions ump
            INNER JOIN system_modules sm ON sm.id = ump.module_id
            WHERE ump.user_id = :user_id
            ORDER BY sm.sort_order ASC, sm.name ASC
        ');
        $stmt->execute(['user_id' => $userId]);

        return array_map(static fn($row) => (string) $row['slug'], $stmt->fetchAll());
    }

    public static function syncUserPermissions(int $userId, array $slugs): void
    {
        $slugs = array_values(array_unique(array_filter(array_map('strval', $slugs))));

        $delete = Database::connection()->prepare('DELETE FROM user_module_permissions WHERE user_id = :user_id');
        $delete->execute(['user_id' => $userId]);

        if ($slugs === []) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($slugs), '?'));
        $stmt = Database::connection()->prepare("SELECT id, slug FROM system_modules WHERE slug IN ($placeholders)");
        $stmt->execute($slugs);
        $modules = $stmt->fetchAll();

        if (!$modules) {
            return;
        }

        $insert = Database::connection()->prepare('
            INSERT INTO user_module_permissions (user_id, module_id)
            VALUES (:user_id, :module_id)
        ');

        foreach ($modules as $module) {
            $insert->execute([
                'user_id' => $userId,
                'module_id' => (int) $module['id'],
            ]);
        }
    }
}
