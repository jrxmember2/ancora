<?php
declare(strict_types=1);

final class ClientAttachment
{
    public static function listFor(string $relatedType, int $relatedId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_attachments WHERE related_type = :related_type AND related_id = :related_id ORDER BY created_at DESC');
        $stmt->execute(['related_type' => $relatedType, 'related_id' => $relatedId]);
        return $stmt->fetchAll() ?: [];
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_attachments WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO client_attachments (related_type, related_id, file_role, original_name, stored_name, relative_path, mime_type, file_size, uploaded_by) VALUES (:related_type, :related_id, :file_role, :original_name, :stored_name, :relative_path, :mime_type, :file_size, :uploaded_by)');
        $stmt->execute($data);
        return Database::lastInsertId('client_attachments');
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM client_attachments WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
