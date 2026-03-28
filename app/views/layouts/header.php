<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authUser = $_SESSION['auth_user'] ?? null;

if (!$authUser) {
    return;
}

$currentRoute = $currentRoute ?? ($_GET['route'] ?? 'hub');
$isHubPage = $currentRoute === 'hub' || $currentRoute === 'desktop';
$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$isSuperadmin = isset($authUser['role']) && $authUser['role'] === 'superadmin';
$headerSearch = trim((string) ($_GET['q'] ?? ''));
$catalog = require ROOT_PATH . '/app/config/module_catalog.php';

$navLinks = [[
    'label' => 'Hub',
    'href'  => '/hub',
    'icon'  => 'fa-solid fa-table-cells-large',
    'match' => 'hub',
]];

foreach ($catalog as $module) {
    $slug = (string) ($module['slug'] ?? '');

    if (in_array($slug, ['config', 'logs', 'dashboard'], true)) {
        continue;
    }

    if (!module_enabled($slug) || !user_has_module_access($slug, $authUser)) {
        continue;
    }

    $navLinks[] = [
        'label' => $module['short_name'] ?? $module['name'],
        'href'  => $module['route_prefix'],
        'icon'  => $module['icon_class'],
        'match' => $slug,
    ];
}

if ($isSuperadmin && module_enabled('config')) {
    $navLinks[] = [
        'label' => 'Config',
        'href'  => '/config',
        'icon'  => 'fa-solid fa-gear',
        'match' => 'config',
    ];
}

$routeIsActive = static function (string $currentRoute, string $match): bool {
    return $currentRoute === $match || str_starts_with($currentRoute, $match . '/');
};

$themeIconClass = ($currentTheme ?? 'dark') === 'light'
    ? 'fa-solid fa-moon'
    : 'fa-solid fa-sun';

$themeLabel = ($currentTheme ?? 'dark') === 'light'
    ? 'Ativar tema escuro'
    : 'Ativar tema claro';

$logoUrl = ($currentTheme ?? 'dark') === 'light'
    ? ($brandLogoLightUrl ?? base_url('/imgs/logomarca.svg'))
    : ($brandLogoDarkUrl ?? base_url('/imgs/logomarca.svg'));

$userName = trim((string) ($authUser['name'] ?? 'Usuário'));
$userEmail = trim((string) ($authUser['email'] ?? ''));
$userRole = trim((string) ($authUser['role'] ?? ''));
$userInitial = mb_strtoupper(mb_substr($userName !== '' ? $userName : 'U', 0, 1));
?>

<header class="main-header<?= $isHubPage ? ' main-header--hub' : ''; ?>">
    <div class="main-header__glow"></div>

    <div class="container header-container">
        <div class="header-branding">
            <a class="logo" href="<?= htmlspecialchars($baseUrl . '/hub', ENT_QUOTES, 'UTF-8'); ?>" aria-label="Ir para o hub">
                <img id="brandLogo" src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($appDisplayName ?? 'Empresa', ENT_QUOTES, 'UTF-8'); ?>">
            </a>
        </div>

        <?php if (!$isHubPage && module_enabled('busca') && user_has_module_access('busca', $authUser)): ?>
        <form class="header-search" action="<?= htmlspecialchars($baseUrl . '/busca', ENT_QUOTES, 'UTF-8'); ?>" method="get" role="search">
            <label class="sr-only" for="headerSearch">Buscar</label>
            <i class="fa-solid fa-magnifying-glass header-search__icon" aria-hidden="true"></i>
            <input id="headerSearch" class="header-search__input" type="search" name="q" value="<?= htmlspecialchars($headerSearch, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Buscar no sistema">
            <button class="header-search__button" type="submit">Buscar</button>
        </form>
        <?php endif; ?>

        <?php if (!$isHubPage): ?>
        <nav class="main-nav" id="mainNav">
            <ul>
                <?php foreach ($navLinks as $link): ?>
                    <?php $isActive = $routeIsActive($currentRoute, $link['match']); ?>
                    <li>
                        <a href="<?= htmlspecialchars($baseUrl . $link['href'], ENT_QUOTES, 'UTF-8'); ?>" class="<?= $isActive ? 'active' : ''; ?>">
                            <i class="<?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
                            <span><?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-mobile-only">
                    <a href="<?= htmlspecialchars($baseUrl . '/logout', ENT_QUOTES, 'UTF-8'); ?>" id="logoutLinkMobile">
                        <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                        <span>Sair</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <div class="header-actions">
            <div class="header-user" title="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="header-user__avatar"><?= htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="header-user__meta">
                    <strong><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></strong>
                    <span><?= htmlspecialchars($userRole !== '' ? ucfirst($userRole) : $userEmail, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>

            <button class="theme-toggle" id="themeToggle" type="button" aria-label="<?= htmlspecialchars($themeLabel, ENT_QUOTES, 'UTF-8'); ?>" title="<?= htmlspecialchars($themeLabel, ENT_QUOTES, 'UTF-8'); ?>">
                <i id="themeToggleIcon" class="<?= htmlspecialchars($themeIconClass, ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
            </button>

            <a href="<?= htmlspecialchars($baseUrl . '/logout', ENT_QUOTES, 'UTF-8'); ?>" class="nav-link--icon-only header-logout" id="logoutLink" title="Sair" aria-label="Sair">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                <span class="sr-only">Sair</span>
            </a>

            <?php if (!$isHubPage): ?>
            <button class="menu-toggle" id="menuToggle" type="button" aria-label="Abrir menu" aria-expanded="false" aria-controls="mainNav">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <?php endif; ?>
        </div>
    </div>
</header>
