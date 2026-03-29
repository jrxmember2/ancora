<?php
declare(strict_types=1);

final class ClientUnit
{
    public static function counts(): array
    {
        $sql = 'SELECT COUNT(*) AS total, SUM(CASE WHEN tenant_entity_id IS NOT NULL THEN 1 ELSE 0 END) AS rented_total FROM client_units';
        return Database::connection()->query($sql)->fetch() ?: [];
    }

    public static function allFiltered(array $filters = []): array
    {
        $where = [];
        $params = [];
        if (!empty($filters['q'])) {
            $where[] = '(u.unit_number LIKE :q OR c.name LIKE :q OR o.display_name LIKE :q OR t.display_name LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['condominium_id'])) {
            $where[] = 'u.condominium_id = :condominium_id';
            $params['condominium_id'] = (int) $filters['condominium_id'];
        }
        if (!empty($filters['block_id'])) {
            $where[] = 'u.block_id = :block_id';
            $params['block_id'] = (int) $filters['block_id'];
        }
        if (!empty($filters['unit_type_id'])) {
            $where[] = 'u.unit_type_id = :unit_type_id';
            $params['unit_type_id'] = (int) $filters['unit_type_id'];
        }
        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql = 'SELECT u.*, c.name AS condominium_name, b.name AS block_name, ty.name AS unit_type_name,
                       o.display_name AS owner_name, o.cpf_cnpj AS owner_document,
                       t.display_name AS tenant_name, t.cpf_cnpj AS tenant_document
                FROM client_units u
                INNER JOIN client_condominiums c ON c.id = u.condominium_id
                LEFT JOIN client_condominium_blocks b ON b.id = u.block_id
                LEFT JOIN client_types ty ON ty.id = u.unit_type_id
                LEFT JOIN client_entities o ON o.id = u.owner_entity_id
                LEFT JOIN client_entities t ON t.id = u.tenant_entity_id
                ' . $whereSql . ' ORDER BY c.name ASC, b.name ASC, u.unit_number ASC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_units WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO client_units (
            condominium_id, block_id, unit_type_id, unit_number, owner_entity_id, tenant_entity_id, owner_notes, tenant_notes, created_by, updated_by
        ) VALUES (
            :condominium_id, :block_id, :unit_type_id, :unit_number, :owner_entity_id, :tenant_entity_id, :owner_notes, :tenant_notes, :created_by, :updated_by
        )');
        $stmt->execute([
            'condominium_id' => (int) $data['condominium_id'],
            'block_id' => $data['block_id'] ?: null,
            'unit_type_id' => $data['unit_type_id'] ?: null,
            'unit_number' => $data['unit_number'],
            'owner_entity_id' => $data['owner_entity_id'] ?: null,
            'tenant_entity_id' => $data['tenant_entity_id'] ?: null,
            'owner_notes' => $data['owner_notes'] ?: null,
            'tenant_notes' => $data['tenant_notes'] ?: null,
            'created_by' => (int) $data['created_by'],
            'updated_by' => (int) $data['updated_by'],
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare('UPDATE client_units SET
            condominium_id = :condominium_id,
            block_id = :block_id,
            unit_type_id = :unit_type_id,
            unit_number = :unit_number,
            owner_entity_id = :owner_entity_id,
            tenant_entity_id = :tenant_entity_id,
            owner_notes = :owner_notes,
            tenant_notes = :tenant_notes,
            updated_by = :updated_by
            WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'condominium_id' => (int) $data['condominium_id'],
            'block_id' => $data['block_id'] ?: null,
            'unit_type_id' => $data['unit_type_id'] ?: null,
            'unit_number' => $data['unit_number'],
            'owner_entity_id' => $data['owner_entity_id'] ?: null,
            'tenant_entity_id' => $data['tenant_entity_id'] ?: null,
            'owner_notes' => $data['owner_notes'] ?: null,
            'tenant_notes' => $data['tenant_notes'] ?: null,
            'updated_by' => (int) $data['updated_by'],
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM client_units WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
