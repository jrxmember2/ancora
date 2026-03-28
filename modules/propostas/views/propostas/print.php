<?php
$companyName = Setting::get('app_company', 'Empresa') ?: 'Empresa';
$companyAddress = Setting::get('company_address', '') ?: '';
$companyPhone = Setting::get('company_phone', '') ?: '';
$companyEmail = Setting::get('company_email', '') ?: '';
$logoPath = Setting::get('branding_logo_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
$logoUrl = preg_match('~^(https?:)?//~', $logoPath) ? $logoPath : base_url($logoPath);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Proposta <?= htmlspecialchars($proposal['proposal_code'] ?? ('#' . (int) $proposal['id'])); ?></title>
    <style>
        body{font-family:Arial,sans-serif;color:#111;margin:0;padding:0;background:#fff}
        .print-wrap{max-width:900px;margin:0 auto;padding:30px 30px 90px}
        .print-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;border-bottom:2px solid #941415;padding-bottom:18px}
        .print-header img{max-height:72px;width:auto}
        .print-title{text-align:right}
        .print-title h1{margin:0 0 6px;font-size:28px}
        .print-title p{margin:0;color:#555}
        .print-block{margin-bottom:20px}
        .print-block h2{font-size:16px;margin:0 0 10px;color:#941415;border-bottom:1px solid #ddd;padding-bottom:6px}
        .print-table{width:100%;border-collapse:collapse}
        .print-table th,.print-table td{border:1px solid #ddd;padding:10px;vertical-align:top;text-align:left}
        .print-table th{background:#f5f5f5;width:22%}
        .print-notes{border:1px solid #ddd;padding:14px;min-height:90px;white-space:pre-wrap}
        .print-footer{
            position:fixed;
            bottom:0;
            left:0;
            right:0;
            border-top:1px solid #ccc;
            padding:12px 24px;
            font-size:12px;
            color:#555;
            background:#fff;
        }
        .print-footer__inner{max-width:900px;margin:0 auto;display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap}
    </style>
</head>
<body onload="window.print()">
<div class="print-wrap">
    <div class="print-header">
        <div>
            <img src="<?= htmlspecialchars($logoUrl); ?>" alt="<?= htmlspecialchars($companyName); ?>">
        </div>
        <div class="print-title">
            <h1>Proposta <?= htmlspecialchars($proposal['proposal_code'] ?? ('#' . (int) $proposal['id'])); ?></h1>
            <p><?= htmlspecialchars(date_br($proposal['proposal_date'])); ?></p>
        </div>
    </div>

    <div class="print-block">
        <h2>Dados principais</h2>
        <table class="print-table">
            <tr><th>Cliente</th><td><?= htmlspecialchars($proposal['client_name']); ?></td><th>Serviço</th><td><?= htmlspecialchars($proposal['service_name']); ?></td></tr>
            <tr><th>Administradora</th><td><?= htmlspecialchars($proposal['administradora_name']); ?></td><th>Status</th><td><?= htmlspecialchars($proposal['status_name']); ?></td></tr>
            <tr><th>Valor da proposta</th><td><?= htmlspecialchars(money_br((float) $proposal['proposal_total'])); ?></td><th>Valor fechado</th><td><?= $proposal['closed_total'] !== null ? htmlspecialchars(money_br((float) $proposal['closed_total'])) : '-'; ?></td></tr>
        </table>
    </div>

    <div class="print-block">
        <h2>Contato</h2>
        <table class="print-table">
            <tr><th>Solicitante</th><td><?= htmlspecialchars($proposal['requester_name']); ?></td><th>Telefone</th><td><?= htmlspecialchars($proposal['requester_phone'] ?: '-'); ?></td></tr>
            <tr><th>E-mail</th><td><?= htmlspecialchars($proposal['contact_email'] ?: '-'); ?></td><th>Indicação</th><td><?= (int) ($proposal['has_referral'] ?? 0) === 1 ? htmlspecialchars($proposal['referral_name'] ?: 'Sim') : 'Não'; ?></td></tr>
            <tr><th>Forma de envio</th><td><?= htmlspecialchars($proposal['send_method_name']); ?></td><th>Follow-up</th><td><?= htmlspecialchars(date_br($proposal['followup_date'])); ?></td></tr>
            <tr><th>Validade</th><td colspan="3"><?= (int) $proposal['validity_days']; ?> dia(s)</td></tr>
        </table>
    </div>

    <div class="print-block">
        <h2>Observações</h2>
        <div class="print-notes"><?= nl2br(htmlspecialchars($proposal['notes'] ?: '-')); ?></div>
    </div>
</div>

<div class="print-footer">
    <div class="print-footer__inner">
        <span><?= htmlspecialchars($companyName); ?></span>
        <?php if ($companyAddress !== ''): ?><span><?= htmlspecialchars($companyAddress); ?></span><?php endif; ?>
        <?php if ($companyPhone !== ''): ?><span><?= htmlspecialchars($companyPhone); ?></span><?php endif; ?>
        <?php if ($companyEmail !== ''): ?><span><?= htmlspecialchars($companyEmail); ?></span><?php endif; ?>
    </div>
</div>
</body>
</html>