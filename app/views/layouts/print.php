<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars(($title ?? APP_NAME) . ' | ' . APP_NAME, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(base_url('/assets/css/app.css')); ?>">
</head>
<body class="print-layout-body">
    <main class="print-layout">
        <?php require $viewFile; ?>
    </main>
    <script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 250);
    });
    </script>
</body>
</html>
