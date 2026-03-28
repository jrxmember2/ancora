<?php
declare(strict_types=1);

final class ClientEntity
{
    private static function decodeRow(?array $row): ?array
    {
        if (!$row) {
            return null;
        }

        foreach (['phones_json' => 'phones', 'emails_json' => 'emails', 'primary_address_json' => 'primary_address', 'billing_address_json' => 'billing_address', 'shareholders_json' => 'shareholders'] as $column => $alias) {
            $row[$alias] = [];
            if (!empty($row[$column])) {
                $decoded = json_decode((string) $row[$column], true);
                if (is_array($decoded)) {
                    $row[$alias] = $decoded;
                }
            }
        }

        return $row;
    }

    private static function encodePayload(array $data): array
    {
        return [
            'entity_type' => $data['entity_type'],
            'profile_scope' => $data['profile_scope'],
            'role_tag' => $data['role_tag'],
            'display_name' => $data['display_name'],
            'legal_name' => $data['legal_name'] ?: null,
            'cpf_cnpj' => $data['cpf_cnpj'] ?: null,
            'rg_ie' => $data['rg_ie'] ?: null,
            'gender' => $data['gender'] ?: null,
            'nationality' => $data['nationality'] ?: null,
            'birth_date' => $data['birth_date'] ?: null,
            'profession' => $data['profession'] ?: null,
            'marital_status' => $data['marital_status'] ?: null,
            'pis' => $data['pis'] ?: null,
            'spouse_name' => $data['spouse_name'] ?: null,
            'father_name' => $data['father_name'] ?: null,
            'mother_name' => $data['mother_name'] ?: null,
            'children_info' => $data['children_info'] ?: null,
            'ctps' => $data['ctps'] ?: null,
            'cnae' => $data['cnae'] ?: null,
            'state_registration' => $data['state_registration'] ?: null,
            'municipal_registration' => $data['municipal_registration'] ?: null,
            'opening_date' => $data['opening_date'] ?: null,
            'legal_representative' => $data['legal_representative'] ?: null,
            'phones_json' => json_encode(array_values($data['phones'] ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'emails_json' => json_encode(array_values($data['emails'] ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'primary_address_json' => json_encode($data['primary_address'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'billing_address_json' => json_encode($data['billing_address'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'shareholders_json' => json_encode(array_values($data['shareholders'] ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'notes' => $data['notes'] ?: null,
            'description' => $data['description'] ?: null,
            'is_active' => $data['is_active'] ? 1 : 0,
            'inactive_reason' => $data['inactive_reason'] ?: null,
            'contract_end_date' => $data['contract_end_date'] ?: null,
            'created_by' => (int) $data['created_by'],
            'updated_by' => (int) $data['updated_by'],
        ];
    }

    public static function counts(): array
    {
        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS active_total,
                    SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) AS inactive_total,
                    SUM(CASE WHEN profile_scope = 'avulso' THEN 1 ELSE 0 END) AS avulsos_total,
                    SUM(CASE WHEN role_tag = 'sindico' THEN 1 ELSE 0 END) AS sindicos_total,
                    SUM(CASE WHEN role_tag = 'administradora' THEN 1 ELSE 0 END) AS administradoras_total
                FROM client_entities";
        return Database::connection()->query($sql)->fetch() ?: [];
    }

    public static function allFiltered(array $filters = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(' . Database::ciLike('display_name', ':q') . ' OR ' . Database::ciLike('legal_name', ':q') . ' OR ' . Database::ciLike('cpf_cnpj', ':q') . ' OR ' . Database::ciLike('rg_ie', ':q') . ')';
            $params['q'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['profile_scope'])) {
            $where[] = 'profile_scope = :profile_scope';
            $params['profile_scope'] = $filters['profile_scope'];
        }
        if (!empty($filters['entity_type'])) {
            $where[] = 'entity_type = :entity_type';
            $params['entity_type'] = $filters['entity_type'];
        }
        if (!empty($filters['role_tag'])) {
            $where[] = 'role_tag = :role_tag';
            $params['role_tag'] = $filters['role_tag'];
        }
        if (($filters['is_active'] ?? '') !== '' && $filters['is_active'] !== null) {
            $where[] = 'is_active = :is_active';
            $params['is_active'] = (int) $filters['is_active'];
        }

        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $sql = 'SELECT * FROM client_entities' . $whereSql . ' ORDER BY display_name ASC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        return array_values(array_filter(array_map([self::class, 'decodeRow'], $items)));
    }

    public static function dropdown(array $filters = []): array
    {
        return self::allFiltered($filters);
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM client_entities WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return self::decodeRow($stmt->fetch() ?: null);
    }

    public static function create(array $data): int
    {
        $payload = self::encodePayload($data);
        $sql = 'INSERT INTO client_entities (
                    entity_type, profile_scope, role_tag, display_name, legal_name, cpf_cnpj, rg_ie, gender, nationality,
                    birth_date, profession, marital_status, pis, spouse_name, father_name, mother_name, children_info,
                    ctps, cnae, state_registration, municipal_registration, opening_date, legal_representative,
                    phones_json, emails_json, primary_address_json, billing_address_json, shareholders_json, notes,
                    description, is_active, inactive_reason, contract_end_date, created_by, updated_by
                ) VALUES (
                    :entity_type, :profile_scope, :role_tag, :display_name, :legal_name, :cpf_cnpj, :rg_ie, :gender, :nationality,
                    :birth_date, :profession, :marital_status, :pis, :spouse_name, :father_name, :mother_name, :children_info,
                    :ctps, :cnae, :state_registration, :municipal_registration, :opening_date, :legal_representative,
                    :phones_json, :emails_json, :primary_address_json, :billing_address_json, :shareholders_json, :notes,
                    :description, :is_active, :inactive_reason, :contract_end_date, :created_by, :updated_by
                )';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($payload);

        return Database::lastInsertId('client_entities');
    }

    public static function update(int $id, array $data): bool
    {
        $payload = self::encodePayload($data);
        $payload['id'] = $id;
        $sql = 'UPDATE client_entities SET
                    entity_type = :entity_type,
                    profile_scope = :profile_scope,
                    role_tag = :role_tag,
                    display_name = :display_name,
                    legal_name = :legal_name,
                    cpf_cnpj = :cpf_cnpj,
                    rg_ie = :rg_ie,
                    gender = :gender,
                    nationality = :nationality,
                    birth_date = :birth_date,
                    profession = :profession,
                    marital_status = :marital_status,
                    pis = :pis,
                    spouse_name = :spouse_name,
                    father_name = :father_name,
                    mother_name = :mother_name,
                    children_info = :children_info,
                    ctps = :ctps,
                    cnae = :cnae,
                    state_registration = :state_registration,
                    municipal_registration = :municipal_registration,
                    opening_date = :opening_date,
                    legal_representative = :legal_representative,
                    phones_json = :phones_json,
                    emails_json = :emails_json,
                    primary_address_json = :primary_address_json,
                    billing_address_json = :billing_address_json,
                    shareholders_json = :shareholders_json,
                    notes = :notes,
                    description = :description,
                    is_active = :is_active,
                    inactive_reason = :inactive_reason,
                    contract_end_date = :contract_end_date,
                    updated_by = :updated_by
                WHERE id = :id';
        $stmt = Database::connection()->prepare($sql);
        return $stmt->execute($payload);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM client_entities WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function duplicateHints(?int $ignoreId, string $document, string $name): array
    {
        $where = [];
        $params = [];
        if ($document !== '') {
            $where[] = 'cpf_cnpj = :cpf_cnpj';
            $params['cpf_cnpj'] = $document;
        }
        if ($name !== '') {
            $where[] = 'display_name = :display_name';
            $params['display_name'] = $name;
        }
        if ($where === []) {
            return [];
        }
        $sql = 'SELECT id, display_name, cpf_cnpj, profile_scope, role_tag FROM client_entities WHERE (' . implode(' OR ', $where) . ')';
        if ($ignoreId) {
            $sql .= ' AND id <> :ignore_id';
            $params['ignore_id'] = $ignoreId;
        }
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }
}
