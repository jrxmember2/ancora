<?php
declare(strict_types=1);

final class ClientType
{
    public static function allByScope(string $scope): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_types WHERE scope = :scope ORDER BY sort_order ASC, name ASC');
        $stmt->execute(['scope' => $scope]);
        return $stmt->fetchAll() ?: [];
    }

    public static function create(string $scope, string $name): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO client_types (scope, name, is_active, sort_order) VALUES (:scope, :name, 1, 999)');
        $stmt->execute(['scope' => $scope, 'name' => $name]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_types WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
