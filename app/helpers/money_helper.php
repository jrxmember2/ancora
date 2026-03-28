<?php
declare(strict_types=1);

function money_to_db(?string $value): ?float
{
    if ($value === null || trim($value) === '') {
        return null;
    }

    $normalized = preg_replace('/[^\d,.-]/', '', $value);
    $normalized = str_replace('.', '', $normalized);
    $normalized = str_replace(',', '.', $normalized);

    return is_numeric($normalized) ? (float) $normalized : null;
}

function money_br(?float $value): string
{
    return 'R$ ' . number_format((float) $value, 2, ',', '.');
}
