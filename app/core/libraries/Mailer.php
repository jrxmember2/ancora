<?php
declare(strict_types=1);

final class Mailer
{
    public static function send(string $to, string $subject, string $htmlBody, ?string $plainBody = null): bool
    {
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . APP_NAME . ' <no-reply@rebecamedina.com.br>';

        return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $htmlBody, implode("\r\n", $headers));
    }
}
