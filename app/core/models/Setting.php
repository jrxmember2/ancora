<?php
declare(strict_types=1);

final class Setting
{
    private static ?array $cache = null;

    private static function loadCache(): void
    {
        if (self::$cache !== null) {
            return;
        }

        self::$cache = [];
        $rows = Database::connection()->query('SELECT setting_key, setting_value FROM app_settings')->fetchAll();

        foreach ($rows as $row) {
            self::$cache[$row['setting_key']] = $row['setting_value'];
        }
    }

    private static function upsertSql(): string
    {
        if (Database::isPgsql()) {
            return 'INSERT INTO app_settings (setting_key, setting_value, description)
                    VALUES (:key, :value, :description)
                    ON CONFLICT (setting_key) DO UPDATE SET
                        setting_value = EXCLUDED.setting_value,
                        description = EXCLUDED.description';
        }

        return 'INSERT INTO app_settings (setting_key, setting_value, description)
                VALUES (:key, :value, :description)
                ON DUPLICATE KEY UPDATE
                    setting_value = VALUES(setting_value),
                    description = VALUES(description)';
    }

    public static function all(): array
    {
        self::loadCache();
        return self::$cache ?? [];
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        self::loadCache();
        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, ?string $value, ?string $description = null): bool
    {
        $stmt = Database::connection()->prepare(self::upsertSql());

        $ok = $stmt->execute([
            'key' => $key,
            'value' => $value,
            'description' => $description,
        ]);

        if ($ok) {
            self::$cache = null;
        }

        return $ok;
    }

    public static function setMany(array $settings): void
    {
        $pdo = Database::connection();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(self::upsertSql());

            foreach ($settings as $key => $payload) {
                $value = is_array($payload) ? ($payload['value'] ?? null) : $payload;
                $description = is_array($payload) ? ($payload['description'] ?? null) : null;

                $stmt->execute([
                    'key' => $key,
                    'value' => $value,
                    'description' => $description,
                ]);
            }

            $pdo->commit();
            self::$cache = null;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
