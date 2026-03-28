<?php
declare(strict_types=1);

function auth_user(): ?array
{
    return $_SESSION['auth_user'] ?? null;
}

function auth_check(): bool
{
    return !empty($_SESSION['auth_user']);
}

function auth_id(): ?int
{
    return isset($_SESSION['auth_user']['id']) ? (int) $_SESSION['auth_user']['id'] : null;
}

function is_superadmin(): bool
{
    return (($_SESSION['auth_user']['role'] ?? '') === 'superadmin');
}

function require_auth(): void
{
    if (!auth_check()) {
        redirect('/login');
    }
}

function require_superadmin(): void
{
    require_auth();
    if (!is_superadmin()) {
        http_response_code(403);
        View::render('errors/403', ['title' => 'Acesso negado']);
        exit;
    }
}
