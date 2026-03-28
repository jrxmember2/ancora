<?php
declare(strict_types=1);

final class ConfigController extends BaseController
{
    public function index(): void
    {
        require_superadmin();
        require_enabled_module('config');

        $logoLightPath = Setting::get('branding_logo_light_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
        $logoLightUrl = preg_match('~^(https?:)?//~', $logoLightPath) ? $logoLightPath : base_url($logoLightPath);

        $logoDarkPath = Setting::get('branding_logo_dark_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
        $logoDarkUrl = preg_match('~^(https?:)?//~', $logoDarkPath) ? $logoDarkPath : base_url($logoDarkPath);

        $faviconPath = Setting::get('branding_favicon_path', '/favicon.ico') ?: '/favicon.ico';
        $faviconUrl = preg_match('~^(https?:)?//~', $faviconPath) ? $faviconPath : base_url($faviconPath);
        $premiumLogoVariant = Setting::get('branding_premium_logo_variant', 'light') ?: 'light';
        $premiumLogoVariant = $premiumLogoVariant === 'dark' ? 'dark' : 'light';

        $this->view('propostas/views/config/index', [
            'title' => 'Configurações',
            'currentRoute' => 'config',
            'administradoras' => Administradora::all(),
            'servicos' => Servico::all(),
            'statusRetorno' => StatusRetorno::all(),
            'formasEnvio' => FormaEnvio::all(),
            'users' => User::all(),
            'modules' => Module::all(),
            'moduleCatalog' => require ROOT_PATH . '/app/config/module_catalog.php',
            'branding' => [
                'company_name' => Setting::get('app_company', 'Rebeca Medina Soluções Jurídicas') ?: '',
                'company_address' => Setting::get('company_address', '') ?: '',
                'company_phone' => Setting::get('company_phone', '') ?: '',
                'company_email' => Setting::get('company_email', '') ?: '',
                'logo_light_path' => $logoLightPath,
                'logo_light_url' => $logoLightUrl,
                'logo_dark_path' => $logoDarkPath,
                'logo_dark_url' => $logoDarkUrl,
                'premium_logo_variant' => $premiumLogoVariant,
                'logo_height_desktop' => (int) Setting::get('branding_logo_height_desktop', '44'),
                'logo_height_mobile' => (int) Setting::get('branding_logo_height_mobile', '36'),
                'logo_height_login' => (int) Setting::get('branding_logo_height_login', '82'),
                'favicon_path' => $faviconPath,
                'favicon_url' => $faviconUrl,
                
            ],
        ]);
    }

    public function saveBranding(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        $companyName = trim((string) $this->post('company_name', ''));
        $companyAddress = trim((string) $this->post('company_address', ''));
        $companyPhone = trim((string) $this->post('company_phone', ''));
        $companyEmail = trim((string) $this->post('company_email', ''));
        $premiumLogoVariant = $this->post('premium_logo_variant', 'light') === 'dark' ? 'dark' : 'light';

        $desktopHeight = max(20, min(140, (int) $this->post('logo_height_desktop', 44)));
        $mobileHeight = max(20, min(120, (int) $this->post('logo_height_mobile', 36)));
        $loginHeight = max(30, min(220, (int) $this->post('logo_height_login', 82)));

        $currentLightPath = Setting::get('branding_logo_light_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
        $currentDarkPath = Setting::get('branding_logo_dark_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';

        $newLightPath = $currentLightPath;
        $newDarkPath = $currentDarkPath;

        $uploadDir = ROOT_PATH . '/assets/uploads/branding';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            $_SESSION['flash_error'] = 'Não foi possível criar a pasta de branding.';
            $this->back();
        }

        if (!empty($_FILES['branding_logo_light']['name'] ?? '')) {
            $file = $_FILES['branding_logo_light'];

            if ((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $_SESSION['flash_error'] = 'Falha no upload da logo do tema claro.';
                $this->back();
            }

            $allowedExtensions = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
            $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                $_SESSION['flash_error'] = 'A logo do tema claro deve estar em SVG, PNG, JPG, JPEG ou WEBP.';
                $this->back();
            }

            $size = (int) ($file['size'] ?? 0);
            if ($size <= 0 || $size > 3 * 1024 * 1024) {
                $_SESSION['flash_error'] = 'A logo do tema claro deve ter no máximo 3 MB.';
                $this->back();
            }

            $fileName = 'logo-light-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
            $destination = $uploadDir . '/' . $fileName;

            if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
                $_SESSION['flash_error'] = 'Não foi possível salvar a logo do tema claro.';
                $this->back();
            }

            $newLightPath = '/assets/uploads/branding/' . $fileName;

            if (str_starts_with($currentLightPath, '/assets/uploads/branding/') && is_file(ROOT_PATH . $currentLightPath)) {
                @unlink(ROOT_PATH . $currentLightPath);
            }
        }

        if (!empty($_FILES['branding_logo_dark']['name'] ?? '')) {
            $file = $_FILES['branding_logo_dark'];

            if ((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $_SESSION['flash_error'] = 'Falha no upload da logo do tema escuro.';
                $this->back();
            }

            $allowedExtensions = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
            $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                $_SESSION['flash_error'] = 'A logo do tema escuro deve estar em SVG, PNG, JPG, JPEG ou WEBP.';
                $this->back();
            }

            $size = (int) ($file['size'] ?? 0);
            if ($size <= 0 || $size > 3 * 1024 * 1024) {
                $_SESSION['flash_error'] = 'A logo do tema escuro deve ter no máximo 3 MB.';
                $this->back();
            }

            $fileName = 'logo-dark-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
            $destination = $uploadDir . '/' . $fileName;

            if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
                $_SESSION['flash_error'] = 'Não foi possível salvar a logo do tema escuro.';
                $this->back();
            }

            $newDarkPath = '/assets/uploads/branding/' . $fileName;

            if (str_starts_with($currentDarkPath, '/assets/uploads/branding/') && is_file(ROOT_PATH . $currentDarkPath)) {
                @unlink(ROOT_PATH . $currentDarkPath);
            }
        }

        Setting::setMany([
            'app_company' => [
                'value' => $companyName,
                'description' => 'Nome da empresa exibido no sistema',
            ],
            'company_address' => [
                'value' => $companyAddress,
                'description' => 'Endereço exibido no rodapé e PDF',
            ],
            'company_phone' => [
                'value' => $companyPhone,
                'description' => 'Telefone exibido no rodapé e PDF',
            ],
            'company_email' => [
                'value' => $companyEmail,
                'description' => 'E-mail exibido no rodapé e PDF',
            ],
            'branding_logo_light_path' => [
                'value' => $newLightPath,
                'description' => 'Logo usada no tema claro',
            ],
            'branding_logo_dark_path' => [
                'value' => $newDarkPath,
                'description' => 'Logo usada no tema escuro',
            ],
            'branding_premium_logo_variant' => [
                'value' => $premiumLogoVariant,
                'description' => 'Logo escolhida para o preview/PDF premium',
            ],
            'branding_logo_height_desktop' => [
                'value' => (string) $desktopHeight,
                'description' => 'Altura da logo no header desktop',
            ],
            'branding_logo_height_mobile' => [
                'value' => (string) $mobileHeight,
                'description' => 'Altura da logo no header mobile',
            ],
            'branding_logo_height_login' => [
                'value' => (string) $loginHeight,
                'description' => 'Altura da logo na tela de login',
            ],
        ]);

        $_SESSION['flash_success'] = 'Branding atualizado com sucesso.';
        $this->back();
    }

    public function saveFavicon(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        $currentPath = Setting::get('branding_favicon_path', '/favicon.ico') ?: '/favicon.ico';
        $newFaviconPath = $currentPath;

        if (empty($_FILES['branding_favicon']['name'] ?? '')) {
            $_SESSION['flash_error'] = 'Selecione um favicon para enviar.';
            $this->back();
        }

        $file = $_FILES['branding_favicon'];

        if ((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Falha no upload do favicon.';
            $this->back();
        }

        $allowedExtensions = ['ico', 'png', 'svg'];
        $originalName = (string) ($file['name'] ?? '');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            $_SESSION['flash_error'] = 'Envie o favicon em ICO, PNG ou SVG.';
            $this->back();
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > 1024 * 1024) {
            $_SESSION['flash_error'] = 'O favicon deve ter no máximo 1 MB.';
            $this->back();
        }

        $uploadDir = ROOT_PATH . '/assets/uploads/branding';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            $_SESSION['flash_error'] = 'Não foi possível criar a pasta de branding.';
            $this->back();
        }

        $fileName = 'favicon-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destination = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
            $_SESSION['flash_error'] = 'Não foi possível salvar o favicon.';
            $this->back();
        }

        $newFaviconPath = '/assets/uploads/branding/' . $fileName;

        if (str_starts_with($currentPath, '/assets/uploads/branding/') && is_file(ROOT_PATH . $currentPath)) {
            @unlink(ROOT_PATH . $currentPath);
        }

        Setting::set(
            'branding_favicon_path',
            $newFaviconPath,
            'Caminho público do favicon do sistema'
        );

        $_SESSION['flash_success'] = 'Favicon atualizado com sucesso.';
        $this->back();
    }

    public function saveModules(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        $enabledIds = $_POST['enabled_modules'] ?? [];
        $enabledIds = array_map('intval', (array) $enabledIds);

        foreach (Module::all() as $module) {
            $mustStayEnabled = in_array($module['slug'], ['dashboard', 'propostas', 'config'], true);
            $enabled = $mustStayEnabled || in_array((int) $module['id'], $enabledIds, true);

            Module::setStatus((int) $module['id'], $enabled);
        }

        $_SESSION['flash_success'] = 'Módulos atualizados com sucesso.';
        $this->back();
    }

    private function back(): void
    {
        redirect('/config');
    }

    private function on(): int
    {
        return $this->post('is_active') ? 1 : 0;
    }

    private function checked(string $key): int
    {
        return $this->post($key) ? 1 : 0;
    }

    public function storeAdministradora(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        Administradora::create([
            'name' => $this->post('name'),
            'type' => $this->post('type', 'administradora'),
            'contact_name' => $this->post('contact_name'),
            'phone' => $this->post('phone'),
            'email' => $this->post('email'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Administradora cadastrada.';
        $this->back();
    }

    public function updateAdministradora(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        Administradora::update((int) $id, [
            'name' => $this->post('name'),
            'type' => $this->post('type', 'administradora'),
            'contact_name' => $this->post('contact_name'),
            'phone' => $this->post('phone'),
            'email' => $this->post('email'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Administradora atualizada.';
        $this->back();
    }

    public function deleteAdministradora(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        Administradora::delete((int) $id);

        $_SESSION['flash_success'] = 'Administradora excluída.';
        $this->back();
    }

    public function storeServico(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        Servico::create([
            'name' => $this->post('name'),
            'description' => $this->post('description'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Serviço cadastrado.';
        $this->back();
    }

    public function updateServico(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        Servico::update((int) $id, [
            'name' => $this->post('name'),
            'description' => $this->post('description'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Serviço atualizado.';
        $this->back();
    }

    public function deleteServico(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        Servico::delete((int) $id);

        $_SESSION['flash_success'] = 'Serviço excluído.';
        $this->back();
    }

    public function storeStatus(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        StatusRetorno::create([
            'system_key' => $this->post('system_key'),
            'name' => $this->post('name'),
            'color_hex' => $this->post('color_hex', '#999999'),
            'requires_closed_value' => $this->checked('requires_closed_value'),
            'requires_refusal_reason' => $this->checked('requires_refusal_reason'),
            'stop_followup_alert' => $this->checked('stop_followup_alert'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Status cadastrado.';
        $this->back();
    }

    public function updateStatus(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        StatusRetorno::update((int) $id, [
            'system_key' => $this->post('system_key'),
            'name' => $this->post('name'),
            'color_hex' => $this->post('color_hex', '#999999'),
            'requires_closed_value' => $this->checked('requires_closed_value'),
            'requires_refusal_reason' => $this->checked('requires_refusal_reason'),
            'stop_followup_alert' => $this->checked('stop_followup_alert'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Status atualizado.';
        $this->back();
    }

    public function deleteStatus(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        StatusRetorno::delete((int) $id);

        $_SESSION['flash_success'] = 'Status excluído.';
        $this->back();
    }

    public function storeFormaEnvio(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        FormaEnvio::create([
            'name' => $this->post('name'),
            'icon_class' => $this->post('icon_class'),
            'color_hex' => $this->post('color_hex', '#999999'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Forma de envio cadastrada.';
        $this->back();
    }

    public function updateFormaEnvio(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        FormaEnvio::update((int) $id, [
            'name' => $this->post('name'),
            'icon_class' => $this->post('icon_class'),
            'color_hex' => $this->post('color_hex', '#999999'),
            'is_active' => $this->on(),
            'sort_order' => (int) $this->post('sort_order', 0),
        ]);

        $_SESSION['flash_success'] = 'Forma de envio atualizada.';
        $this->back();
    }

    public function deleteFormaEnvio(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        FormaEnvio::delete((int) $id);

        $_SESSION['flash_success'] = 'Forma de envio excluída.';
        $this->back();
    }

    public function storeUsuario(): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        $userId = User::create([
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'theme_preference' => 'dark',
            'password_hash' => password_hash((string) $this->post('password'), PASSWORD_DEFAULT),
            'role' => $this->post('role', 'comum'),
            'is_active' => $this->on(),
            'is_protected' => 0,
        ]);

        $permissions = (array) ($_POST['module_permissions'] ?? []);
        if ($this->post('role', 'comum') === 'superadmin') {
            $permissions = [];
        }
        Module::syncUserPermissions($userId, $permissions);

        $_SESSION['flash_success'] = 'Usuário cadastrado.';
        $this->back();
    }

    public function updateUsuario(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        $userId = (int) $id;
        $target = User::find($userId);

        if (!$target) {
            $_SESSION['flash_error'] = 'Usuário não encontrado.';
            $this->back();
        }

        User::update($userId, [
            'name' => $this->post('name'),
            'theme_preference' => $target['theme_preference'] ?? 'dark',
            'email' => $this->post('email'),
            'role' => $this->post('role', 'comum'),
            'is_active' => $this->on(),
            'password_hash' => $this->post('password') ? password_hash((string) $this->post('password'), PASSWORD_DEFAULT) : null,
        ]);

        $role = $this->post('role', 'comum');
        $permissions = $role === 'superadmin' ? [] : (array) ($_POST['module_permissions'] ?? []);
        Module::syncUserPermissions($userId, $permissions);

        if (auth_id() === $userId) {
            $_SESSION['auth_user']['module_permissions'] = $role === 'superadmin' ? [] : Module::permissionsByUser($userId);
            $_SESSION['auth_user']['role'] = $role;
        }

        $_SESSION['flash_success'] = 'Usuário atualizado.';
        $this->back();
    }

    public function deleteUsuario(string $id): void
    {
        require_superadmin();
        require_enabled_module('config');
        $this->validateCsrfOrFail();

        $userId = (int) $id;
        $target = User::find($userId);

        if ($target && (int) $target['is_protected'] === 1) {
            $_SESSION['flash_error'] = 'Os superadmins principais não podem ser excluídos.';
            $this->back();
        }

        User::delete($userId);

        $_SESSION['flash_success'] = 'Usuário excluído.';
        $this->back();
    }
}