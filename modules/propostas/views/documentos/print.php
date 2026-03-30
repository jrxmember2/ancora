<?php $renderData = $render; ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($renderData['document']['document_title'] ?? 'Proposta Premium'); ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(base_url('/assets/css/proposal-template-aquarela.css')); ?>">
</head>
<body class="proposal-print" onload="window.print()">
    <?php require ROOT_PATH . '/modules/propostas/views/documentos/templates/aquarela_master.php'; ?>
</body>
</html>