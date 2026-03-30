<?php
declare(strict_types=1);

function env(string $key, mixed $default = null): mixed
{
    static $loaded = false;
    static $values = [];

    if (!$loaded) {
        $loaded = true;
        $envFile = ROOT_PATH . '/.env';
        if (is_file($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$k, $v] = array_map('trim', explode('=', $line, 2));
                $v = trim($v, "\"'");
                $values[$k] = $v;
                $_ENV[$k] = $v;
            }
        }
    }

    return $values[$key] ?? $_ENV[$key] ?? getenv($key) ?: $default;
}

function bootstrap_app(): void
{
    require_once APP_PATH . '/config/app.php';
    require_once APP_PATH . '/helpers/url_helper.php';
    require_once APP_PATH . '/helpers/auth_helper.php';
    require_once APP_PATH . '/helpers/csrf_helper.php';
    require_once APP_PATH . '/helpers/log_helper.php';
    require_once APP_PATH . '/helpers/money_helper.php';
    require_once APP_PATH . '/helpers/date_helper.php';
    require_once APP_PATH . '/helpers/module_helper.php';

    $files = [
        APP_PATH . '/core/libraries/Database.php',
        APP_PATH . '/core/libraries/View.php',
        APP_PATH . '/core/libraries/Auth.php',
        APP_PATH . '/core/libraries/Router.php',
        APP_PATH . '/core/libraries/Mailer.php',
        APP_PATH . '/core/controllers/BaseController.php',
        APP_PATH . '/core/controllers/AuthController.php',
        APP_PATH . '/core/controllers/HomeController.php',
        APP_PATH . '/core/controllers/ErrorController.php',
        APP_PATH . '/core/controllers/SearchController.php',
        APP_PATH . '/core/controllers/ThemeController.php',
        APP_PATH . '/core/models/User.php',
        APP_PATH . '/core/models/AuditLog.php',
        APP_PATH . '/core/models/Setting.php',
        APP_PATH . '/core/models/Module.php',
    ];

    foreach ($files as $file) {
        require_once $file;
    }

    foreach (glob(MODULES_PATH . '/*/models/*.php') ?: [] as $file) {
        require_once $file;
    }
    foreach (glob(MODULES_PATH . '/*/services/*.php') ?: [] as $file) {
        require_once $file;
    }
    foreach (glob(MODULES_PATH . '/*/controllers/*.php') ?: [] as $file) {
        require_once $file;
    }
}
