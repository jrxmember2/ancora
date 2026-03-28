<?php
declare(strict_types=1);

final class PropostaHistorico
{
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO proposta_history (proposta_id, user_id, user_email, action, summary, payload_json)
                VALUES (:proposta_id, :user_id, :user_email, :action, :summary, :payload_json)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'proposta_id' => $data['proposta_id'],
            'user_id' => $data['user_id'],
            'user_email' => $data['user_email'],
            'action' => $data['action'],
            'summary' => $data['summary'],
            'payload_json' => $data['payload_json'],
        ]);

        return Database::lastInsertId('proposta_history');
    }

    public static function byProposal(int $propostaId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM proposta_history WHERE proposta_id = :proposta_id ORDER BY created_at DESC, id DESC');
        $stmt->execute(['proposta_id' => $propostaId]);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['payload'] = [];
            if (!empty($row['payload_json'])) {
                $decoded = json_decode((string) $row['payload_json'], true);
                $row['payload'] = is_array($decoded) ? $decoded : [];
            }
        }

        return $rows;
    }
}
