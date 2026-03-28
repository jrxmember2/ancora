<?php
declare(strict_types=1);

function base_url(string $path = ''): string
{
    $base = rtrim(BASE_URL, '/');
    $path = '/' . ltrim($path, '/');
    return $base . ($path === '/' ? '' : $path);
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function current_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?: '';

    if ($basePath && str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath));
    }

    return '/' . ltrim($uri, '/');
}

function current_query(array $overrides = [], array $remove = []): string
{
    $query = $_GET ?? [];

    foreach ($remove as $key) {
        unset($query[$key]);
    }

    foreach ($overrides as $key => $value) {
        if ($value === null || $value === '') {
            unset($query[$key]);
            continue;
        }

        $query[$key] = $value;
    }

    return http_build_query($query);
}

function url_with_query(string $path, array $overrides = [], array $remove = []): string
{
    $query = current_query($overrides, $remove);
    return base_url($path) . ($query !== '' ? '?' . $query : '');
}


function asset_url(string $path = ''): string
{
    return base_url('/' . ltrim($path, '/'));
}
