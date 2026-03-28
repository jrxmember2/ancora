<?php
declare(strict_types=1);

final class PropostaAnexo
{
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO proposta_attachments (proposta_id, original_name, stored_name, relative_path, mime_type, file_size, uploaded_by)
                VALUES (:proposta_id, :original_name, :stored_name, :relative_path, :mime_type, :file_size, :uploaded_by)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'proposta_id' => $data['proposta_id'],
            'original_name' => $data['original_name'],
            'stored_name' => $data['stored_name'],
            'relative_path' => $data['relative_path'],
            'mime_type' => $data['mime_type'],
            'file_size' => $data['file_size'],
            'uploaded_by' => $data['uploaded_by'],
        ]);

        return Database::lastInsertId('proposta_attachments');
    }

    public static function byProposal(int $propostaId): array
    {
        $sql = 'SELECT pa.*, u.name AS uploaded_by_name
                FROM proposta_attachments pa
                INNER JOIN users u ON u.id = pa.uploaded_by
                WHERE pa.proposta_id = :proposta_id
                ORDER BY pa.created_at DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['proposta_id' => $propostaId]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $sql = 'SELECT pa.*, u.name AS uploaded_by_name
                FROM proposta_attachments pa
                INNER JOIN users u ON u.id = pa.uploaded_by
                WHERE pa.id = :id
                LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByProposal(int $propostaId, int $attachmentId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM proposta_attachments WHERE id = :id AND proposta_id = :proposta_id LIMIT 1');
        $stmt->execute(['id' => $attachmentId, 'proposta_id' => $propostaId]);
        return $stmt->fetch() ?: null;
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM proposta_attachments WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function countByProposal(int $propostaId): int
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM proposta_attachments WHERE proposta_id = :proposta_id');
        $stmt->execute(['proposta_id' => $propostaId]);
        return (int) $stmt->fetchColumn();
    }
}
