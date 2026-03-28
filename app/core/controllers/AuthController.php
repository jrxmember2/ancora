<?php
declare(strict_types=1);

final class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (auth_check()) {
            redirect('/hub');
        }

        View::render('auth/login', [
            'title' => 'Login',
            'error' => $_SESSION['flash_error'] ?? null,
        ], 'layouts/master');

        unset($_SESSION['flash_error']);
    }

    public function login(): void
    {
        $this->validateCsrfOrFail();

        $email = trim((string) $this->post('email'));
        $password = (string) $this->post('password');

        if (Auth::attempt($email, $password)) {
            redirect('/hub');
        }

        $_SESSION['flash_error'] = 'Credenciais inválidas ou usuário inativo.';
        redirect('/login');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/login');
    }
}
