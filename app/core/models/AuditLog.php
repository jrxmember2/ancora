<?php
declare(strict_types=1);

final class AuditLog
{
    public static function create(array $data): int
    {
        $sql = 'INSERT INTO audit_logs (user_id, user_email, action, entity_type, entity_id, details, ip_address, user_agent)
                VALUES (:user_id, :user_email, :action, :entity_type, :entity_id, :details, :ip_address, :user_agent)';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'user_id' => $data['user_id'],
            'user_email' => $data['user_email'],
            'action' => $data['action'],
            'entity_type' => $data['entity_type'],
            'entity_id' => $data['entity_id'],
            'details' => $data['details'],
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
        ]);

        return Database::lastInsertId('audit_logs');
    }

    public static function paginateFiltered(array $filters, int $page = 1, int $perPage = 50): array
    {
        $page = max(1, $page);
        $perPage = max(10, min(200, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(' . Database::ciLike('user_email', ':q') . ' OR ' . Database::ciLike('action', ':q') . ' OR ' . Database::ciLike('details', ':q') . ')';
            $params['q'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['action'])) {
            $where[] = 'action = :action';
            $params['action'] = $filters['action'];
        }
        if (!empty($filters['user_email'])) {
            $where[] = 'user_email = :user_email';
            $params['user_email'] = $filters['user_email'];
        }
        if (!empty($filters['date_from'])) {
            $where[] = Database::dateOnly('created_at') . ' >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where[] = Database::dateOnly('created_at') . ' <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }

        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

        $countStmt = Database::connection()->prepare('SELECT COUNT(*) FROM audit_logs' . $whereSql);
        foreach ($params as $k => $v) {
            $countStmt->bindValue(':' . $k, $v);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        $stmt = Database::connection()->prepare('SELECT * FROM audit_logs' . $whereSql . ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
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
        ];
    }

    public static function distinctActions(): array
    {
        return Database::connection()->query('SELECT DISTINCT action FROM audit_logs ORDER BY action ASC')->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public static function distinctUsers(): array
    {
        return Database::connection()->query('SELECT DISTINCT user_email FROM audit_logs ORDER BY user_email ASC')->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public static function globalSearch(string $term, int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));

        $stmt = Database::connection()->prepare('
        SELECT id, user_email, action, details, created_at
        FROM audit_logs
        WHERE ' . Database::ciLike('user_email', ':q1') . '
           OR ' . Database::ciLike('action', ':q2') . '
           OR ' . Database::ciLike('details', ':q3') . '
        ORDER BY created_at DESC
        LIMIT :limit
    ');

        $like = '%' . $term . '%';

        $stmt->bindValue(':q1', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q2', $like, PDO::PARAM_STR);
        $stmt->bindValue(':q3', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
