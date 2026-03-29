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

<header class="navbar sticky top-0 z-50 bg-base-100 border-b border-base-300 shadow-sm">
    <div class="flex-1">
        <a href="<?= htmlspecialchars($baseUrl . '/hub', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-ghost normal-case text-xl px-2" aria-label="Ir para o hub">
            <img id="brandLogo" src="<?= htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($appDisplayName ?? 'Empresa', ENT_QUOTES, 'UTF-8'); ?>" class="h-10 w-auto object-contain">
        </a>
    </div>

    <?php if (!$isHubPage && module_enabled('busca') && user_has_module_access('busca', $authUser)): ?>
    <form class="form-control" action="<?= htmlspecialchars($baseUrl . '/busca', ENT_QUOTES, 'UTF-8'); ?>" method="get" role="search">
        <div class="input-group">
            <input id="headerSearch" type="search" name="q" value="<?= htmlspecialchars($headerSearch, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Buscar..." class="input input-bordered input-sm w-24 md:w-auto" />
            <button type="submit" class="btn btn-square btn-sm">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            </button>
        </div>
    </form>
    <?php endif; ?>

    <div class="flex-none gap-2">
        <div class="dropdown dropdown-end hidden md:block">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center font-bold">
                    <?= htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </label>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                <li class="menu-title">
                    <span><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></span>
                </li>
                <li><a><?= htmlspecialchars($userRole !== '' ? ucfirst($userRole) : $userEmail, ENT_QUOTES, 'UTF-8'); ?></a></li>
                <li><hr /></li>
                <li><a href="<?= htmlspecialchars($baseUrl . '/logout', ENT_QUOTES, 'UTF-8'); ?>"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Sair</a></li>
            </ul>
        </div>

        <button class="btn btn-ghost btn-circle" id="themeToggle" type="button" aria-label="<?= htmlspecialchars($themeLabel, ENT_QUOTES, 'UTF-8'); ?>" title="<?= htmlspecialchars($themeLabel, ENT_QUOTES, 'UTF-8'); ?>">
            <i id="themeToggleIcon" class="<?= htmlspecialchars($themeIconClass, ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
        </button>

        <a href="<?= htmlspecialchars($baseUrl . '/logout', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-ghost btn-circle hidden md:flex" id="logoutLink" title="Sair" aria-label="Sair">
            <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
        </a>

        <?php if (!$isHubPage): ?>
        <div class="dropdown dropdown-end md:hidden">
            <label tabindex="0" class="btn btn-ghost btn-circle" id="menuToggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="mainNav">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </label>
            <ul tabindex="0" id="mainNav" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                <?php foreach ($navLinks as $link): ?>
                    <?php $isActive = $routeIsActive($currentRoute, $link['match']); ?>
                    <li>
                        <a href="<?= htmlspecialchars($baseUrl . $link['href'], ENT_QUOTES, 'UTF-8'); ?>" class="<?= $isActive ? 'active' : ''; ?>">
                            <i class="<?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
                            <span><?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><hr /></li>
                <li><a href="<?= htmlspecialchars($baseUrl . '/logout', ENT_QUOTES, 'UTF-8'); ?>" id="logoutLinkMobile"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Sair</a></li>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!$isHubPage): ?>
    <nav class="hidden md:flex">
        <ul class="menu menu-horizontal px-1">
            <?php foreach ($navLinks as $link): ?>
                <?php $isActive = $routeIsActive($currentRoute, $link['match']); ?>
                <li>
                    <a href="<?= htmlspecialchars($baseUrl . $link['href'], ENT_QUOTES, 'UTF-8'); ?>" class="<?= $isActive ? 'active' : ''; ?>">
                        <i class="<?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
                        <span><?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php endif; ?>
</header>
