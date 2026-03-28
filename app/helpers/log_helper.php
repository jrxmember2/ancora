<?php
declare(strict_types=1);

function app_log(?int $userId, string $userEmail, string $action, ?string $entityType = null, ?int $entityId = null, ?string $details = null): void
{
    AuditLog::create([
        'user_id' => $userId,
        'user_email' => $userEmail,
        'action' => $action,
        'entity_type' => $entityType,
        'entity_id' => $entityId,
        'details' => $details,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
}
