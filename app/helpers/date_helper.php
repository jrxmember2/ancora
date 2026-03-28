<?php
declare(strict_types=1);

function date_br(?string $date): string
{
    if (!$date) {
        return '-';
    }

    $ts = strtotime($date);
    return $ts ? date('d/m/Y', $ts) : '-';
}

function today(): string
{
    return date('Y-m-d');
}
