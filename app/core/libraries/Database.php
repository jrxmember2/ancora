<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $instance = null;
    private static ?array $config = null;

    public static function config(): array
    {
        if (self::$config === null) {
            self::$config = require CONFIG_PATH . '/database.php';
        }

        return self::$config;
    }

    public static function driver(): string
    {
        return strtolower((string) (self::config()['driver'] ?? 'mysql'));
    }

    public static function isMysql(): bool
    {
        return self::driver() === 'mysql';
    }

    public static function isPgsql(): bool
    {
        return in_array(self::driver(), ['pgsql', 'postgres', 'postgresql'], true);
    }

    public static function connection(): PDO
    {
        if (self::$instance === null) {
            $config = self::config();
            $driver = self::driver();

            if (self::isPgsql()) {
                $dsn = sprintf(
                    'pgsql:host=%s;port=%s;dbname=%s',
                    $config['host'],
                    $config['port'],
                    $config['dbname']
                );
            } else {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $config['host'],
                    $config['port'],
                    $config['dbname'],
                    $config['charset'] ?? 'utf8mb4'
                );
            }

            self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            if ($driver === 'mysql') {
                self::$instance->exec("SET NAMES '" . ($config['charset'] ?? 'utf8mb4') . "'");
            }
        }

        return self::$instance;
    }

    public static function currentTimestamp(): string
    {
        return 'CURRENT_TIMESTAMP';
    }

    public static function currentDate(): string
    {
        return 'CURRENT_DATE';
    }

    public static function year(string $expression): string
    {
        return 'EXTRACT(YEAR FROM ' . $expression . ')';
    }

    public static function month(string $expression): string
    {
        return 'EXTRACT(MONTH FROM ' . $expression . ')';
    }

    public static function dateOnly(string $expression): string
    {
        return self::isPgsql() ? '(' . $expression . ')::date' : 'DATE(' . $expression . ')';
    }

    public static function ciLike(string $expression, string $placeholder): string
    {
        return 'LOWER(COALESCE(' . $expression . ", '')) LIKE LOWER(" . $placeholder . ')';
    }

    public static function dateDiffInDays(string $futureExpression, string $pastExpression): string
    {
        if (self::isPgsql()) {
            return '((' . $futureExpression . ')::date - (' . $pastExpression . ')::date)';
        }

        return 'DATEDIFF(' . $futureExpression . ', ' . $pastExpression . ')';
    }

    public static function addDays(string $dateExpression, string $daysExpression): string
    {
        if (self::isPgsql()) {
            return "((" . $dateExpression . ")::date + ((" . $daysExpression . ")::int * INTERVAL '1 day'))";
        }

        return 'DATE_ADD(' . $dateExpression . ', INTERVAL ' . $daysExpression . ' DAY)';
    }

    public static function lastInsertId(?string $table = null): int
    {
        $pdo = self::connection();

        if (self::isPgsql()) {
            try {
                if ($table) {
                    return (int) $pdo->lastInsertId($table . '_id_seq');
                }

                return (int) $pdo->query('SELECT LASTVAL()')->fetchColumn();
            } catch (Throwable) {
                return (int) $pdo->query('SELECT LASTVAL()')->fetchColumn();
            }
        }

        return (int) $pdo->lastInsertId();
    }
}
