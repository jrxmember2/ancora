<?php
declare(strict_types=1);

final class ClientCondominium
{
    private static function decodeRow(?array $row): ?array
    {
        if (!$row) {
            return null;
        }
        $row['address'] = [];
        if (!empty($row['address_json'])) {
            $decoded = json_decode((string) $row['address_json'], true);
            if (is_array($decoded)) {
                $row['address'] = $decoded;
            }
        }
        return $row;
    }

    public static function counts(): array
    {
        $sql = 'SELECT COUNT(*) AS total, SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active_total, SUM(CASE WHEN has_blocks = 1 THEN 1 ELSE 0 END) AS with_blocks_total FROM client_condominiums';
        return Database::connection()->query($sql)->fetch() ?: [];
    }

    public static function allFiltered(array $filters = []): array
    {
        $where = [];
        $params = [];
        if (!empty($filters['q'])) {
            $where[] = '(' . Database::ciLike('c.name', ':q') . ' OR ' . Database::ciLike('c.cnpj', ':q') . ')';
            $params['q'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['type_id'])) {
            $where[] = 'c.condominium_type_id = :type_id';
            $params['type_id'] = (int) $filters['type_id'];
        }
        if (!empty($filters['syndico_entity_id'])) {
            $where[] = 'c.syndico_entity_id = :syndico_entity_id';
            $params['syndico_entity_id'] = (int) $filters['syndico_entity_id'];
        }
        if (($filters['is_active'] ?? '') !== '' && $filters['is_active'] !== null) {
            $where[] = 'c.is_active = :is_active';
            $params['is_active'] = (int) $filters['is_active'];
        }
        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql = 'SELECT c.*, t.name AS condominium_type_name, s.display_name AS syndico_name, a.display_name AS administradora_name,
                       (SELECT COUNT(*) FROM client_units u WHERE u.condominium_id = c.id) AS units_total,
                       (SELECT COUNT(*) FROM client_condominium_blocks b WHERE b.condominium_id = c.id) AS blocks_total
                FROM client_condominiums c
                LEFT JOIN client_types t ON t.id = c.condominium_type_id
                LEFT JOIN client_entities s ON s.id = c.syndico_entity_id
                LEFT JOIN client_entities a ON a.id = c.administradora_entity_id
                ' . $whereSql . ' ORDER BY c.name ASC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return array_values(array_filter(array_map([self::class, 'decodeRow'], $stmt->fetchAll() ?: [])));
    }

    public static function dropdown(): array
    {
        $stmt = Database::connection()->query('SELECT id, name, has_blocks FROM client_condominiums WHERE is_active = 1 ORDER BY name ASC');
        return $stmt->fetchAll() ?: [];
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_condominiums WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $item = self::decodeRow($stmt->fetch() ?: null);
        if ($item) {
            $item['blocks'] = ClientBlock::byCondominium($id);
        }
        return $item;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO client_condominiums (
            name, condominium_type_id, has_blocks, cnpj, cnae, state_registration, municipal_registration, address_json,
            syndico_entity_id, administradora_entity_id, bank_details, characteristics, is_active, inactive_reason, contract_end_date,
            created_by, updated_by
        ) VALUES (
            :name, :condominium_type_id, :has_blocks, :cnpj, :cnae, :state_registration, :municipal_registration, :address_json,
            :syndico_entity_id, :administradora_entity_id, :bank_details, :characteristics, :is_active, :inactive_reason, :contract_end_date,
            :created_by, :updated_by
        )');
        $stmt->execute([
            'name' => $data['name'],
            'condominium_type_id' => $data['condominium_type_id'] ?: null,
            'has_blocks' => $data['has_blocks'] ? 1 : 0,
            'cnpj' => $data['cnpj'] ?: null,
            'cnae' => $data['cnae'] ?: null,
            'state_registration' => $data['state_registration'] ?: null,
            'municipal_registration' => $data['municipal_registration'] ?: null,
            'address_json' => json_encode($data['address'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'syndico_entity_id' => $data['syndico_entity_id'] ?: null,
            'administradora_entity_id' => $data['administradora_entity_id'] ?: null,
            'bank_details' => $data['bank_details'] ?: null,
            'characteristics' => $data['characteristics'] ?: null,
            'is_active' => $data['is_active'] ? 1 : 0,
            'inactive_reason' => $data['inactive_reason'] ?: null,
            'contract_end_date' => $data['contract_end_date'] ?: null,
            'created_by' => (int) $data['created_by'],
            'updated_by' => (int) $data['updated_by'],
        ]);
        return Database::lastInsertId('client_condominiums');
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare('UPDATE client_condominiums SET
            name = :name,
            condominium_type_id = :condominium_type_id,
            has_blocks = :has_blocks,
            cnpj = :cnpj,
            cnae = :cnae,
            state_registration = :state_registration,
            municipal_registration = :municipal_registration,
            address_json = :address_json,
            syndico_entity_id = :syndico_entity_id,
            administradora_entity_id = :administradora_entity_id,
            bank_details = :bank_details,
            characteristics = :characteristics,
            is_active = :is_active,
            inactive_reason = :inactive_reason,
            contract_end_date = :contract_end_date,
            updated_by = :updated_by
            WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'condominium_type_id' => $data['condominium_type_id'] ?: null,
            'has_blocks' => $data['has_blocks'] ? 1 : 0,
            'cnpj' => $data['cnpj'] ?: null,
            'cnae' => $data['cnae'] ?: null,
            'state_registration' => $data['state_registration'] ?: null,
            'municipal_registration' => $data['municipal_registration'] ?: null,
            'address_json' => json_encode($data['address'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'syndico_entity_id' => $data['syndico_entity_id'] ?: null,
            'administradora_entity_id' => $data['administradora_entity_id'] ?: null,
            'bank_details' => $data['bank_details'] ?: null,
            'characteristics' => $data['characteristics'] ?: null,
            'is_active' => $data['is_active'] ? 1 : 0,
            'inactive_reason' => $data['inactive_reason'] ?: null,
            'contract_end_date' => $data['contract_end_date'] ?: null,
            'updated_by' => (int) $data['updated_by'],
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM client_condominiums WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
