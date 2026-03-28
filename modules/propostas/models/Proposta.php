<?php
declare(strict_types=1);

final class Proposta
{
    private static function nextSequenceForYear(int $year): int
    {
        $stmt = Database::connection()->prepare('
        SELECT COALESCE(MAX(proposal_seq), 0) + 1
        FROM propostas
        WHERE proposal_year = :year
    ');
        $stmt->execute(['year' => $year]);

        return (int) $stmt->fetchColumn();
    }

    private static function makeProposalCode(int $seq, int $year): string
    {
        return sprintf('%03d.%d', $seq, $year);
    }
    private static array $historyFields = [
        'proposal_date' => 'Data',
        'client_name' => 'Cliente',
        'administradora_id' => 'Administradora',
        'service_id' => 'Serviço',
        'proposal_total' => 'Valor da proposta',
        'closed_total' => 'Valor fechado',
        'requester_name' => 'Solicitante',
        'requester_phone' => 'Telefone',
        'contact_email' => 'E-mail',
        'has_referral' => 'Houve indicação',
        'referral_name' => 'Nome da indicação',
        'send_method_id' => 'Forma de envio',
        'response_status_id' => 'Status',
        'refusal_reason' => 'Motivo da recusa',
        'followup_date' => 'Data de follow-up',
        'validity_days' => 'Validade',
        'notes' => 'Observações',
    ];

    private static function baseSelect(): string
    {
        return "SELECT p.*,
                       a.name AS administradora_name, a.type AS administradora_type,
                       s.name AS service_name,
                       f.name AS send_method_name, f.icon_class, f.color_hex AS send_method_color,
                       st.name AS status_name, st.color_hex AS status_color, st.system_key AS status_system_key,
                       (SELECT COUNT(*) FROM proposta_attachments pa WHERE pa.proposta_id = p.id) AS attachment_count,
                       (SELECT COUNT(*) FROM proposta_history ph WHERE ph.proposta_id = p.id) AS history_count
                FROM propostas p
                INNER JOIN administradoras a ON a.id = p.administradora_id
                INNER JOIN servicos s ON s.id = p.service_id
                INNER JOIN formas_envio f ON f.id = p.send_method_id
                INNER JOIN status_retorno st ON st.id = p.response_status_id";
    }

    private static function buildFilterWhere(array $filters, array &$params): string
    {
        $where = [];

        if (!empty($filters['q'])) {
            $where[] = '(p.id = :exact_id
    OR ' . Database::ciLike('p.proposal_code', ':q1') . '
    OR ' . Database::ciLike('p.client_name', ':q2') . '
    OR ' . Database::ciLike('p.requester_name', ':q3') . '
    OR ' . Database::ciLike('p.contact_email', ':q4') . '
    OR ' . Database::ciLike('p.referral_name', ':q5') . '
    OR ' . Database::ciLike('a.name', ':q6') . '
    OR ' . Database::ciLike('s.name', ':q7') . '
    OR ' . Database::ciLike('st.name', ':q8') . ')';

            $like = '%' . $filters['q'] . '%';
            $params['q1'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
            $params['q4'] = $like;
            $params['q5'] = $like;
            $params['q6'] = $like;
            $params['q7'] = $like;
            $params['q8'] = $like;
            $params['exact_id'] = ctype_digit((string) $filters['q']) ? (int) $filters['q'] : 0;
        }
        if (!empty($filters['administradora_id'])) {
            $where[] = 'p.administradora_id = :administradora_id';
            $params['administradora_id'] = (int) $filters['administradora_id'];
        }
        if (!empty($filters['service_id'])) {
            $where[] = 'p.service_id = :service_id';
            $params['service_id'] = (int) $filters['service_id'];
        }
        if (!empty($filters['response_status_id'])) {
            $where[] = 'p.response_status_id = :response_status_id';
            $params['response_status_id'] = (int) $filters['response_status_id'];
        }
        if (!empty($filters['send_method_id'])) {
            $where[] = 'p.send_method_id = :send_method_id';
            $params['send_method_id'] = (int) $filters['send_method_id'];
        }
        if (!empty($filters['year'])) {
            $where[] = Database::year('p.proposal_date') . ' = :year';
            $params['year'] = (int) $filters['year'];
        }
        if (!empty($filters['date_from'])) {
            $where[] = 'p.proposal_date >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where[] = 'p.proposal_date <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }

        return $where ? ' WHERE ' . implode(' AND ', $where) : '';
    }

    public static function paginateFiltered(array $filters, int $page = 1, int $perPage = 15): array
    {
        $page = max(1, $page);
        $perPage = max(5, min(100, $perPage));
        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = self::buildFilterWhere($filters, $params);

        $countStmt = Database::connection()->prepare(
            'SELECT COUNT(*) FROM propostas p
             INNER JOIN administradoras a ON a.id = p.administradora_id
             INNER JOIN servicos s ON s.id = p.service_id
             INNER JOIN status_retorno st ON st.id = p.response_status_id' . $where
        );
        foreach ($params as $k => $v) {
            $countStmt->bindValue(':' . $k, $v);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        $sumStmt = Database::connection()->prepare(
            'SELECT COALESCE(SUM(p.proposal_total),0) AS total_amount, COALESCE(SUM(p.closed_total),0) AS total_closed
             FROM propostas p
             INNER JOIN administradoras a ON a.id = p.administradora_id
             INNER JOIN servicos s ON s.id = p.service_id
             INNER JOIN status_retorno st ON st.id = p.response_status_id' . $where
        );
        foreach ($params as $k => $v) {
            $sumStmt->bindValue(':' . $k, $v);
        }
        $sumStmt->execute();
        $totals = $sumStmt->fetch() ?: ['total_amount' => 0, 'total_closed' => 0];

        $sql = self::baseSelect() . $where . ' ORDER BY p.id DESC LIMIT :limit OFFSET :offset';
        $stmt = Database::connection()->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'pages' => max(1, (int) ceil($total / $perPage)),
            'totals' => [
                'proposal_total' => (float) ($totals['total_amount'] ?? 0),
                'closed_total' => (float) ($totals['total_closed'] ?? 0),
            ],
        ];
    }

    public static function allFiltered(array $filters = []): array
    {
        $params = [];
        $where = self::buildFilterWhere($filters, $params);
        $stmt = Database::connection()->prepare(self::baseSelect() . $where . ' ORDER BY p.id DESC');
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $sql = "SELECT p.*,
                       a.name AS administradora_name, a.type AS administradora_type,
                       s.name AS service_name,
                       f.name AS send_method_name, f.icon_class, f.color_hex AS send_method_color,
                       st.name AS status_name, st.color_hex AS status_color, st.system_key AS status_system_key,
                       cu.name AS created_by_name, uu.name AS updated_by_name
                FROM propostas p
                INNER JOIN administradoras a ON a.id = p.administradora_id
                INNER JOIN servicos s ON s.id = p.service_id
                INNER JOIN formas_envio f ON f.id = p.send_method_id
                INNER JOIN status_retorno st ON st.id = p.response_status_id
                INNER JOIN users cu ON cu.id = p.created_by
                LEFT JOIN users uu ON uu.id = p.updated_by
                WHERE p.id = :id
                LIMIT 1";
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }


    public static function update(int $id, array $data): bool
    {
        $sql = 'UPDATE propostas SET
                proposal_date = :proposal_date,
                client_name = :client_name,
                administradora_id = :administradora_id,
                service_id = :service_id,
                proposal_total = :proposal_total,
                closed_total = :closed_total,
                requester_name = :requester_name,
                requester_phone = :requester_phone,
                contact_email = :contact_email,
                has_referral = :has_referral,
                referral_name = :referral_name,
                send_method_id = :send_method_id,
                response_status_id = :response_status_id,
                refusal_reason = :refusal_reason,
                followup_date = :followup_date,
                validity_days = :validity_days,
                notes = :notes,
                updated_by = :updated_by
            WHERE id = :id';

        $stmt = Database::connection()->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'proposal_date' => $data['proposal_date'],
            'client_name' => $data['client_name'],
            'administradora_id' => $data['administradora_id'],
            'service_id' => $data['service_id'],
            'proposal_total' => $data['proposal_total'],
            'closed_total' => $data['closed_total'],
            'requester_name' => $data['requester_name'],
            'requester_phone' => $data['requester_phone'],
            'contact_email' => $data['contact_email'],
            'has_referral' => $data['has_referral'],
            'referral_name' => $data['referral_name'],
            'send_method_id' => $data['send_method_id'],
            'response_status_id' => $data['response_status_id'],
            'refusal_reason' => $data['refusal_reason'],
            'followup_date' => $data['followup_date'],
            'validity_days' => $data['validity_days'],
            'notes' => $data['notes'],
            'updated_by' => $data['updated_by'],
        ]);
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();

        $proposalDate = !empty($data['proposal_date']) ? $data['proposal_date'] : date('Y-m-d');
        $proposalYear = (int) date('Y', strtotime($proposalDate));

        try {
            $pdo->beginTransaction();

            $seqStmt = $pdo->prepare('
            SELECT COALESCE(MAX(proposal_seq), 0) + 1
            FROM propostas
            WHERE proposal_year = :year
            FOR UPDATE
        ');
            $seqStmt->execute(['year' => $proposalYear]);
            $proposalSeq = (int) $seqStmt->fetchColumn();

            $proposalCode = self::makeProposalCode($proposalSeq, $proposalYear);

            $sql = 'INSERT INTO propostas (
                    proposal_year, proposal_seq, proposal_code,
                    proposal_date, client_name, administradora_id, service_id,
                    proposal_total, closed_total, requester_name, requester_phone,
                    contact_email, has_referral, referral_name, send_method_id,
                    response_status_id, refusal_reason, followup_date, validity_days,
                    notes, created_by, updated_by
                ) VALUES (
                    :proposal_year, :proposal_seq, :proposal_code,
                    :proposal_date, :client_name, :administradora_id, :service_id,
                    :proposal_total, :closed_total, :requester_name, :requester_phone,
                    :contact_email, :has_referral, :referral_name, :send_method_id,
                    :response_status_id, :refusal_reason, :followup_date, :validity_days,
                    :notes, :created_by, :updated_by
                )';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'proposal_year' => $proposalYear,
                'proposal_seq' => $proposalSeq,
                'proposal_code' => $proposalCode,
                'proposal_date' => $data['proposal_date'],
                'client_name' => $data['client_name'],
                'administradora_id' => $data['administradora_id'],
                'service_id' => $data['service_id'],
                'proposal_total' => $data['proposal_total'],
                'closed_total' => $data['closed_total'],
                'requester_name' => $data['requester_name'],
                'requester_phone' => $data['requester_phone'],
                'contact_email' => $data['contact_email'],
                'has_referral' => $data['has_referral'] ?? 0,
                'referral_name' => $data['referral_name'] ?? null,
                'send_method_id' => $data['send_method_id'],
                'response_status_id' => $data['response_status_id'],
                'refusal_reason' => $data['refusal_reason'],
                'followup_date' => $data['followup_date'],
                'validity_days' => $data['validity_days'],
                'notes' => $data['notes'],
                'created_by' => $data['created_by'],
                'updated_by' => $data['updated_by'],
            ]);

            $id = Database::lastInsertId('propostas');
            $pdo->commit();

            return $id;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM propostas WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function yearsAvailable(): array
    {
        $rows = Database::connection()->query('SELECT DISTINCT ' . Database::year('proposal_date') . ' AS year FROM propostas ORDER BY year DESC')->fetchAll();
        $years = array_map(static fn(array $row): int => (int) $row['year'], $rows);
        return $years ?: [(int) date('Y')];
    }

    public static function forDashboardDetail(string $type, string $key, int $year): array
    {
        $sql = self::baseSelect() . ' WHERE ' . Database::year('p.proposal_date') . ' = :year';
        $params = ['year' => $year];

        if ($type === 'month') {
            $sql .= ' AND ' . Database::month('p.proposal_date') . ' = :key';
            $params['key'] = (int) $key;
        } elseif ($type === 'service') {
            $sql .= ' AND s.name = :key';
            $params['key'] = $key;
        } elseif ($type === 'administradora') {
            $sql .= ' AND a.name = :key';
            $params['key'] = $key;
        } elseif ($type === 'status') {
            $sql .= ' AND st.name = :key';
            $params['key'] = $key;
        } elseif ($type === 'alerts') {
            $sql .= ' AND p.followup_date IS NOT NULL AND ' . Database::dateDiffInDays('p.followup_date', Database::currentDate()) . ' <= 5 AND st.stop_followup_alert = 0';
        } elseif ($type === 'closed_year') {
            $sql .= ' AND st.system_key = :approved_status';
            $params['approved_status'] = 'approved';
        } elseif ($type === 'total_year') {
            // mantém filtro apenas por ano
        } elseif ($type === 'validity_alerts') {
            $sql .= ' AND p.validity_days > 0 AND ' . Database::addDays('p.proposal_date', 'p.validity_days') . ' <= ' . Database::addDays(Database::currentDate(), '3') . ' AND st.stop_followup_alert = 0';
        }

        $sql .= ' ORDER BY p.id DESC';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function overdueFollowups(): array
    {
        $sql = self::baseSelect() . " WHERE p.followup_date IS NOT NULL
                  AND p.followup_date < ' . Database::currentDate() . '
                  AND st.stop_followup_alert = 0
                ORDER BY p.followup_date ASC";
        return Database::connection()->query($sql)->fetchAll();
    }

    public static function expiringFollowups(): array
    {
        $sql = self::baseSelect() . " WHERE p.followup_date IS NOT NULL
                  AND ' . Database::dateDiffInDays('p.followup_date', Database::currentDate()) . ' BETWEEN 0 AND 5
                  AND st.stop_followup_alert = 0
                ORDER BY p.followup_date ASC";
        return Database::connection()->query($sql)->fetchAll();
    }

    public static function followupAlertsDue(): array
    {
        $sql = "SELECT p.*, st.name AS status_name, st.system_key AS status_system_key
                FROM propostas p
                INNER JOIN status_retorno st ON st.id = p.response_status_id
                WHERE p.followup_date IS NOT NULL
                  AND ' . Database::dateDiffInDays('p.followup_date', Database::currentDate()) . ' <= 5
                  AND st.stop_followup_alert = 0
                ORDER BY p.followup_date ASC, p.id DESC";
        return Database::connection()->query($sql)->fetchAll();
    }

    public static function validityAlertsDue(): array
    {
        $sql = "SELECT p.*, st.name AS status_name, st.system_key AS status_system_key,
                       ' . Database::addDays('p.proposal_date', 'p.validity_days') . ' AS validity_limit_date
                FROM propostas p
                INNER JOIN status_retorno st ON st.id = p.response_status_id
                WHERE p.validity_days > 0
                  AND ' . Database::addDays('p.proposal_date', 'p.validity_days') . ' <= ' . Database::addDays(Database::currentDate(), '3') . '
                  AND st.stop_followup_alert = 0
                ORDER BY validity_limit_date ASC, p.id DESC";
        return Database::connection()->query($sql)->fetchAll();
    }

    public static function globalSearch(string $term, int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));

        $sql = self::baseSelect() . "
        WHERE
            p.id = :exact_id
            OR ' . Database::ciLike('p.proposal_code', ':q1') . '
            OR ' . Database::ciLike('p.client_name', ':q2') . '
            OR ' . Database::ciLike('p.requester_name', ':q3') . '
            OR ' . Database::ciLike('p.contact_email', ':q4') . '
            OR ' . Database::ciLike('p.referral_name', ':q5') . '
            OR ' . Database::ciLike('a.name', ':q6') . '
            OR ' . Database::ciLike('s.name', ':q7') . '
        ORDER BY p.id DESC
        LIMIT :limit
    ";

        $stmt = Database::connection()->prepare($sql);

        $like = '%' . $term . '%';

        $stmt->bindValue(':exact_id', ctype_digit($term) ? (int) $term : 0, PDO::PARAM_INT);
        $stmt->bindValue(':q1', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q2', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q3', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q4', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q5', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q6', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q7', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function snapshotForHistory(int $id): array
    {
        $proposal = self::find($id);
        if (!$proposal) {
            return [];
        }

        return [
            'id' => (int) $proposal['id'],
            'proposal_date' => $proposal['proposal_date'],
            'client_name' => $proposal['client_name'],
            'administradora_id' => (int) $proposal['administradora_id'],
            'administradora_name' => $proposal['administradora_name'],
            'service_id' => (int) $proposal['service_id'],
            'service_name' => $proposal['service_name'],
            'proposal_total' => (float) $proposal['proposal_total'],
            'closed_total' => $proposal['closed_total'] !== null ? (float) $proposal['closed_total'] : null,
            'requester_name' => $proposal['requester_name'],
            'requester_phone' => $proposal['requester_phone'],
            'contact_email' => $proposal['contact_email'],
            'send_method_id' => (int) $proposal['send_method_id'],
            'send_method_name' => $proposal['send_method_name'],
            'response_status_id' => (int) $proposal['response_status_id'],
            'status_name' => $proposal['status_name'],
            'refusal_reason' => $proposal['refusal_reason'],
            'followup_date' => $proposal['followup_date'],
            'validity_days' => (int) $proposal['validity_days'],
            'notes' => $proposal['notes'],
            'has_referral' => (int) $proposal['has_referral'],
            'referral_name' => $proposal['referral_name'],
        ];
    }

    public static function humanDiff(array $before, array $after): array
    {
        $changes = [];

        foreach (self::$historyFields as $field => $label) {
            $old = $before[$field] ?? null;
            $new = $after[$field] ?? null;

            if ((string) $old === (string) $new) {
                continue;
            }

            $changes[] = [
                'field' => $field,
                'label' => $label,
                'from' => self::humanValue($field, $old, $before),
                'to' => self::humanValue($field, $new, $after),
            ];
        }

        return $changes;
    }

    private static function humanValue(string $field, mixed $value, array $context): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        return match ($field) {
            'proposal_total', 'closed_total' => money_br((float) $value),
            'proposal_date', 'followup_date' => date_br((string) $value),
            'administradora_id' => (string) ($context['administradora_name'] ?? $value),
            'service_id' => (string) ($context['service_name'] ?? $value),
            'send_method_id' => (string) ($context['send_method_name'] ?? $value),
            'response_status_id' => (string) ($context['status_name'] ?? $value),
            'validity_days' => (int) $value . ' dia(s)',
            'has_referral' => (int) $value === 1 ? 'Sim' : 'Não',
            default => (string) $value,
        };
    }
}
