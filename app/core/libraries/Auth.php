<?php
declare(strict_types=1);

final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);

        if (!$user || !(int) $user['is_active']) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['auth_user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'theme_preference' => $user['theme_preference'] ?? 'dark',
            'module_permissions' => $user['module_permissions'] ?? [],
];

        User::touchLastLogin((int) $user['id']);

        app_log((int) $user['id'], $user['email'], 'login', 'users', (int) $user['id'], 'Acesso ao sistema');

        return true;
    }

    public static function logout(): void
    {
        $user = auth_user();

        if ($user) {
            app_log((int) $user['id'], $user['email'], 'logout', 'users', (int) $user['id'], 'Saída do sistema');
        }

        unset($_SESSION['auth_user']);
        session_regenerate_id(true);
    }
}
