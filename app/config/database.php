<?php
declare(strict_types=1);

$driver = strtolower((string) env('DB_CONNECTION', 'pgsql'));
$defaultPort = in_array($driver, ['pgsql', 'postgres', 'postgresql'], true) ? '5432' : '3306';
$defaultCharset = in_array($driver, ['pgsql', 'postgres', 'postgresql'], true) ? 'utf8' : 'utf8mb4';

return [
    'driver' => $driver,
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', $defaultPort),
    'dbname' => env('DB_DATABASE', 'ancora'),
    'username' => env('DB_USERNAME', 'postgres'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => env('DB_CHARSET', $defaultCharset),
];
