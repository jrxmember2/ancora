<?php
declare(strict_types=1);

final class AuthMiddleware
{
    public static function handle(): void
    {
        require_auth();
    }
}
