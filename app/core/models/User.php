<?php
declare(strict_types=1);

final class User
{
    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM users ORDER BY is_protected DESC, created_at DESC');
                $users = $stmt->fetchAll();

        foreach ($users as &$user) {
            $user['module_permissions'] = self::modulePermissions((int) $user['id']);
        }

        return $users;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch() ?: null;

        if ($user) {
            $user['module_permissions'] = self::modulePermissions((int) $user['id']);
        }

        return $user;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch() ?: null;

        if ($user) {
            $user['module_permissions'] = self::modulePermissions((int) $user['id']);
        }

        return $user;
    }

    public static function create(array $data): int
    {
        $sql = 'INSERT INTO users (name, email, password_hash, role, theme_preference, is_active, is_protected)
                VALUES (:name, :email, :password_hash, :role, :theme_preference, :is_active, :is_protected)';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'role' => $data['role'],
            'theme_preference' => $data['theme_preference'] ?? 'dark',
            'is_active' => $data['is_active'] ?? 1,
            'is_protected' => $data['is_protected'] ?? 0,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public static function updateThemePreference(int $id, string $theme): bool
    {
        $theme = $theme === 'light' ? 'light' : 'dark';

        $stmt = Database::connection()->prepare('
            UPDATE users
            SET theme_preference = :theme
            WHERE id = :id
        ');

        return $stmt->execute([
            'id' => $id,
            'theme' => $theme,
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $fields = ['name = :name', 'email = :email', 'role = :role', 'is_active = :is_active'];
        $params = [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => $data['is_active'] ?? 1,
        ];

        if (isset($data['theme_preference'])) {
            $fields[] = 'theme_preference = :theme_preference';
            $params['theme_preference'] = $data['theme_preference'] === 'light' ? 'light' : 'dark';
        }

        if (!empty($data['password_hash'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = $data['password_hash'];
        }

        $stmt = Database::connection()->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id');
        return $stmt->execute($params);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function touchLastLogin(int $id): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function modulePermissions(int $id): array
    {
        return Module::permissionsByUser($id);
    }

    public static function globalSearch(string $term, int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));

        $stmt = Database::connection()->prepare('
        SELECT id, name, email, role, is_active
        FROM users
        WHERE name LIKE :q1 OR email LIKE :q2
        ORDER BY is_protected DESC, created_at DESC
        LIMIT :limit
    ');

        $like = '%' . $term . '%';

        $stmt->bindValue(':q1', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q2', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}