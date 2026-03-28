<?php

declare(strict_types=1);

function module_enabled(string $slug): bool
{
    return Module::isEnabled($slug);
}

function user_has_module_access(string $slug, ?array $user = null): bool
{
    $user ??= auth_user();

    if (!$user) {
        return false;
    }

    if (($user['role'] ?? '') === 'superadmin') {
        return true;
    }

    return Module::userHasAccess((int) ($user['id'] ?? 0), $slug);
}

function require_enabled_module(string $slug): void
{
    require_auth();

    if (!module_enabled($slug)) {
        http_response_code(403);
        View::render('errors/403', [
            'title' => 'Módulo desabilitado',
            'message' => 'Este módulo está desabilitado no momento.',
        ]);
        exit;
    }

    if (!user_has_module_access($slug)) {
        http_response_code(403);
        View::render('errors/403', [
            'title' => 'Acesso negado',
            'message' => 'Seu usuário não possui permissão para acessar este módulo.',
        ]);
        exit;
    }
}
