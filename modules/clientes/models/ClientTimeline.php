<?php
declare(strict_types=1);

final class ClientTimeline
{
    public static function listFor(string $relatedType, int $relatedId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_timelines WHERE related_type = :related_type AND related_id = :related_id ORDER BY created_at DESC');
        $stmt->execute(['related_type' => $relatedType, 'related_id' => $relatedId]);
        return $stmt->fetchAll() ?: [];
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO client_timelines (related_type, related_id, note, user_id, user_email) VALUES (:related_type, :related_id, :note, :user_id, :user_email)');
        $stmt->execute($data);
        return (int) Database::connection()->lastInsertId();
    }
}
