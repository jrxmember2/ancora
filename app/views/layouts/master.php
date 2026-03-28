<?php declare(strict_types=1);

$appDisplayName = Setting::get('app_company', 'Empresa') ?: 'Empresa';

$brandLogoLightPath = Setting::get('branding_logo_light_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
$brandLogoLightUrl = preg_match('~^(https?:)?//~', $brandLogoLightPath)
    ? $brandLogoLightPath
    : base_url($brandLogoLightPath);

$brandLogoDarkPath = Setting::get('branding_logo_dark_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
$brandLogoDarkUrl = preg_match('~^(https?:)?//~', $brandLogoDarkPath)
    ? $brandLogoDarkPath
    : base_url($brandLogoDarkPath);

$brandFaviconPath = Setting::get('branding_favicon_path', '/favicon.ico') ?: '/favicon.ico';
$brandFaviconUrl = preg_match('~^(https?:)?//~', $brandFaviconPath)
    ? $brandFaviconPath
    : base_url($brandFaviconPath);

$brandFaviconExt = strtolower(pathinfo(parse_url($brandFaviconPath, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
$brandFaviconMime = match ($brandFaviconExt) {
    'png' => 'image/png',
    'svg' => 'image/svg+xml',
    default => 'image/x-icon',
};

$brandLogoHeightDesktop = max(20, min(140, (int) Setting::get('branding_logo_height_desktop', '44')));
$brandLogoHeightMobile  = max(20, min(120, (int) Setting::get('branding_logo_height_mobile', '36')));
$brandLogoHeightLogin   = max(30, min(220, (int) Setting::get('branding_logo_height_login', '82')));

$companyAddress = Setting::get('company_address', '') ?: '';
$companyPhone   = Setting::get('company_phone', '') ?: '';
$companyEmail   = Setting::get('company_email', '') ?: '';

$currentTheme = 'dark';
if (!empty($_SESSION['auth_user']['theme_preference'])) {
    $currentTheme = $_SESSION['auth_user']['theme_preference'] === 'light' ? 'light' : 'dark';
}
?>
<!doctype html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($currentTheme, ENT_QUOTES, 'UTF-8'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <title><?= htmlspecialchars(($title ?? 'Painel') . ' | ' . $appDisplayName, ENT_QUOTES, 'UTF-8'); ?></title>

    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('theme_preference');
                var theme = (savedTheme === 'light' || savedTheme === 'dark') ? savedTheme : 'dark';
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>

    <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="theme-save-url" content="<?= htmlspecialchars(base_url('/theme/save'), ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="brand-logo-light" content="<?= htmlspecialchars($brandLogoLightUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="brand-logo-dark" content="<?= htmlspecialchars($brandLogoDarkUrl, ENT_QUOTES, 'UTF-8'); ?>">

    <link rel="icon" type="<?= htmlspecialchars($brandFaviconMime, ENT_QUOTES, 'UTF-8'); ?>" href="<?= htmlspecialchars($brandFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="shortcut icon" href="<?= htmlspecialchars($brandFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('/public/build/app.css'), ENT_QUOTES, 'UTF-8'); ?>">
</head>

<body
    class="app-body"
    style="
        --brand-logo-height-desktop: <?= (int) $brandLogoHeightDesktop; ?>px;
        --brand-logo-height-mobile: <?= (int) $brandLogoHeightMobile; ?>px;
        --brand-logo-height-login: <?= (int) $brandLogoHeightLogin; ?>px;
    "
>
    <?php if (auth_check()): ?>
        <div class="app-shell min-h-screen">
            <?php View::partial('layouts/header', [
                'appDisplayName'   => $appDisplayName,
                'brandLogoLightUrl'=> $brandLogoLightUrl,
                'brandLogoDarkUrl' => $brandLogoDarkUrl,
                'currentTheme'     => $currentTheme,
            ]); ?>

            <main class="page-content">
                <div class="container page-content__inner">
                    <?php if (!empty($_SESSION['flash_success'])): ?>
                        <div class="alert-banner alert-banner--success mb-4">
                            <?= htmlspecialchars($_SESSION['flash_success']); ?>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['flash_error'])): ?>
                        <div class="alert-banner alert-banner--danger mb-4">
                            <?= htmlspecialchars($_SESSION['flash_error']); ?>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                    <?php endif; ?>

                    <?php require $viewFile; ?>
                </div>
            </main>

            <footer class="main-footer border-t border-base-300/70 bg-base-100/75 backdrop-blur-sm">
                <div class="container main-footer__inner py-6">
                    <div class="main-footer__brand-block">
                        <div class="main-footer__brand text-sm font-semibold tracking-[0.18em] uppercase text-base-content/62">
                            <?= htmlspecialchars($appDisplayName); ?>
                        </div>
                    </div>

                    <div class="main-footer__meta flex flex-wrap gap-3 text-sm text-base-content/56">
                        <?php if ($companyAddress !== ''): ?>
                            <span><?= htmlspecialchars($companyAddress); ?></span>
                        <?php endif; ?>
                        <?php if ($companyPhone !== ''): ?>
                            <span><?= htmlspecialchars($companyPhone); ?></span>
                        <?php endif; ?>
                        <?php if ($companyEmail !== ''): ?>
                            <span><?= htmlspecialchars($companyEmail); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="container main-footer__bottom flex flex-wrap items-center justify-between gap-3 border-t border-base-300/70 py-4 text-xs text-base-content/48">
                    <span class="main-footer__copyright">&copy; <?= date('Y'); ?> <?= htmlspecialchars($appDisplayName); ?></span>
                    <span class="main-footer__dev">Arquitetura e implantação: Serratech Soluções em TI</span>
                </div>
            </footer>
        </div>
    <?php else: ?>
        <?php require $viewFile; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= htmlspecialchars(base_url('/assets/js/app.js')); ?>"></script>
    <script src="<?= htmlspecialchars(base_url('/assets/js/masks.js')); ?>"></script>
    <script src="<?= htmlspecialchars(base_url('/assets/js/charts.js')); ?>"></script>
    <script src="<?= htmlspecialchars(base_url('/assets/js/propostas.js')); ?>"></script>
    <script src="<?= htmlspecialchars(base_url('/assets/js/proposal-document.js')); ?>"></script>
</body>
</html>
