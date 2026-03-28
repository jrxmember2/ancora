<?php
declare(strict_types=1);

abstract class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    protected function validateCsrfOrFail(): void
    {
        if (!verify_csrf($this->post('_csrf'))) {
            http_response_code(419);
            exit('Token CSRF inválido.');
        }
    }
}
