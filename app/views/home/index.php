<?php
$authUser = $_SESSION['auth_user'] ?? [];
$userName = trim((string) ($authUser['name'] ?? ''));
$firstName = $userName !== '' ? explode(' ', $userName)[0] : 'Usuário';
$hour = (int) date('H');
$greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
?>

<section class="hub-shell" aria-label="Módulos do sistema">
    <div class="hub-heading">
        <div class="hub-welcome">
            <span class="hub-heading__eyebrow">Hub central</span>
            <h1 class="hub-heading__title"><?= htmlspecialchars($greeting . ', ' . $firstName); ?></h1>
            <p class="hub-heading__text">Selecione um módulo para continuar. A nova estrutura já está preparada para crescer com mais serviços, automações e integrações.</p>
        </div>

        <div class="hub-clock" aria-label="Data e hora atuais">
            <strong id="hubClockTime">--:--</strong>
            <span id="hubClockDate">--</span>
        </div>
    </div>

    <section class="hub-grid" aria-label="Cards de módulos">
        <?php foreach ($modules as $module): ?>
            <?php $href = $module['has_real_link'] ? base_url($module['route_prefix']) : '#'; ?>
            <?php $tag = $module['has_real_link'] ? 'a' : 'div'; ?>
            <<?= $tag; ?>
                class="hub-tile hub-tile--<?= htmlspecialchars($module['accent']); ?> hub-tile--<?= htmlspecialchars($module['status']); ?>"
                <?= $module['has_real_link'] ? 'href="' . htmlspecialchars($href) . '"' : ''; ?>
                <?= $module['has_real_link'] ? '' : 'aria-disabled="true"'; ?>
            >
                <span class="hub-tile__shine"></span>
                <span class="hub-tile__corner"></span>

                <div class="hub-tile__icon">
                    <i class="<?= htmlspecialchars($module['icon_class']); ?>" aria-hidden="true"></i>
                </div>

                <div class="hub-tile__body">
                    <span class="hub-tile__badge"><?= htmlspecialchars($module['status_label']); ?></span>
                    <h2><?= htmlspecialchars($module['short_name']); ?></h2>
                    <p><?= htmlspecialchars($module['description']); ?></p>
                </div>
            </<?= $tag; ?>>
        <?php endforeach; ?>
    </section>
</section>

<script>
(function () {
    const timeEl = document.getElementById('hubClockTime');
    const dateEl = document.getElementById('hubClockDate');
    if (!timeEl || !dateEl) return;

    const updateClock = () => {
        const now = new Date();
        timeEl.textContent = now.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit'
        });
        dateEl.textContent = now.toLocaleDateString('pt-BR', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    };

    updateClock();
    setInterval(updateClock, 1000);
})();
</script>
