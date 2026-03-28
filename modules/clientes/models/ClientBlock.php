<?php
declare(strict_types=1);

final class ClientBlock
{
    public static function byCondominium(int $condominiumId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_condominium_blocks WHERE condominium_id = :condominium_id ORDER BY sort_order ASC, name ASC');
        $stmt->execute(['condominium_id' => $condominiumId]);
        return $stmt->fetchAll() ?: [];
    }

    public static function replaceForCondominium(int $condominiumId, array $names): void
    {
        $delete = Database::connection()->prepare('DELETE FROM client_condominium_blocks WHERE condominium_id = :condominium_id');
        $delete->execute(['condominium_id' => $condominiumId]);

        $insert = Database::connection()->prepare('INSERT INTO client_condominium_blocks (condominium_id, name, sort_order) VALUES (:condominium_id, :name, :sort_order)');
        $sort = 1;
        foreach ($names as $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }
            $insert->execute([
                'condominium_id' => $condominiumId,
                'name' => mb_substr($name, 0, 50),
                'sort_order' => $sort++,
            ]);
        }
    }
}
