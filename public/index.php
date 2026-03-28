<?php
declare(strict_types=1);

session_save_path(__DIR__ . '/../storage/sessions');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/config/constants.php';
require_once __DIR__ . '/../scripts/bootstrap.php';
bootstrap_app();

date_default_timezone_set(APP_TIMEZONE);

$router = new Router();
require_once APP_PATH . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
