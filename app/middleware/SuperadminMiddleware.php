<?php
declare(strict_types=1);

final class SuperadminMiddleware
{
    public static function handle(): void
    {
        require_superadmin();
    }
}
