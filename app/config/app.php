<?php
declare(strict_types=1);

if (!defined('APP_NAME')) {
    define('APP_NAME', env('APP_NAME', 'Âncora'));
}

if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim((string) env('APP_URL', 'http://localhost'), '/'));
}

if (!defined('APP_TIMEZONE')) {
    define('APP_TIMEZONE', (string) env('APP_TIMEZONE', 'America/Sao_Paulo'));
}

if (!defined('DEFAULT_MODULE')) {
    define('DEFAULT_MODULE', (string) env('DEFAULT_MODULE', 'propostas'));
}
