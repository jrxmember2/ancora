<?php
declare(strict_types=1);

final class StatusRetorno
{
    public static function active(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM status_retorno WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT * FROM status_retorno ORDER BY sort_order ASC, name ASC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM status_retorno WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO status_retorno (system_key, name, color_hex, requires_closed_value, requires_refusal_reason, stop_followup_alert, is_active, sort_order) VALUES (:system_key, :name, :color_hex, :requires_closed_value, :requires_refusal_reason, :stop_followup_alert, :is_active, :sort_order)');
        $stmt->execute([
            'system_key' => $data['system_key'],
            'name' => $data['name'],
            'color_hex' => $data['color_hex'],
            'requires_closed_value' => $data['requires_closed_value'] ?? 0,
            'requires_refusal_reason' => $data['requires_refusal_reason'] ?? 0,
            'stop_followup_alert' => $data['stop_followup_alert'] ?? 0,
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare('UPDATE status_retorno SET system_key = :system_key, name = :name, color_hex = :color_hex, requires_closed_value = :requires_closed_value, requires_refusal_reason = :requires_refusal_reason, stop_followup_alert = :stop_followup_alert, is_active = :is_active, sort_order = :sort_order WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'system_key' => $data['system_key'],
            'name' => $data['name'],
            'color_hex' => $data['color_hex'],
            'requires_closed_value' => $data['requires_closed_value'] ?? 0,
            'requires_refusal_reason' => $data['requires_refusal_reason'] ?? 0,
            'stop_followup_alert' => $data['stop_followup_alert'] ?? 0,
            'is_active' => $data['is_active'] ?? 1,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM status_retorno WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
