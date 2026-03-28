<?php
declare(strict_types=1);

final class ThemeController extends BaseController
{
    public function save(): void
    {
        require_auth();
        $this->validateCsrfOrFail();

        $theme = (string) ($_POST['theme'] ?? 'dark');
        $theme = $theme === 'light' ? 'light' : 'dark';

        $authUser = $_SESSION['auth_user'] ?? null;

        if (!$authUser || empty($authUser['id'])) {
            $this->json([
                'success' => false,
                'message' => 'Usuário não autenticado.',
            ], 401);
            return;
        }

        $saved = User::updateThemePreference((int) $authUser['id'], $theme);

        if ($saved) {
            $_SESSION['auth_user']['theme_preference'] = $theme;

            $this->json([
                'success' => true,
                'theme' => $theme,
            ]);
            return;
        }

        $this->json([
            'success' => false,
            'message' => 'Não foi possível salvar o tema.',
        ], 500);
    }
}