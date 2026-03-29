<?php
$authUser = $_SESSION['auth_user'] ?? [];
$userName = trim((string) ($authUser['name'] ?? ''));
$firstName = $userName !== '' ? explode(' ', $userName)[0] : 'Usuário';
$hour = (int) date('H');
$greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
?>

<section class="min-h-screen bg-gradient-to-br from-base-100 via-base-100 to-base-200 py-12 px-4" aria-label="Módulos do sistema">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="mb-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-sm font-semibold text-primary mb-2">Bem-vindo</p>
                    <h1 class="text-4xl md:text-5xl font-bold text-base-content mb-2">
                        <?= htmlspecialchars($greeting . ', ' . $firstName); ?>
                    </h1>
                    <p class="text-lg text-base-content/60">Selecione um módulo para continuar trabalhando.</p>
                </div>

                <div class="card bg-base-200 shadow-lg border border-base-300 p-6 min-w-fit">
                    <div class="text-center">
                        <p class="text-sm text-base-content/60 mb-2">Data e Hora</p>
                        <strong id="hubClockTime" class="text-3xl font-bold text-primary block">--:--</strong>
                        <span id="hubClockDate" class="text-sm text-base-content/60 block mt-2">--</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($modules as $module): ?>
                <?php 
                    $href = $module['has_real_link'] ? base_url($module['route_prefix']) : '#';
                    $tag = $module['has_real_link'] ? 'a' : 'div';
                    $isActive = $module['status'] === 'active';
                    $isPlanned = $module['status'] === 'planned';
                    $isInactive = $module['status'] === 'inactive';
                    
                    // Color mapping for accents
                    $colorMap = [
                        'red' => 'from-red-500/10 to-red-600/5 border-red-200 dark:border-red-800',
                        'blue' => 'from-blue-500/10 to-blue-600/5 border-blue-200 dark:border-blue-800',
                        'purple' => 'from-purple-500/10 to-purple-600/5 border-purple-200 dark:border-purple-800',
                        'green' => 'from-green-500/10 to-green-600/5 border-green-200 dark:border-green-800',
                        'orange' => 'from-orange-500/10 to-orange-600/5 border-orange-200 dark:border-orange-800',
                        'slate' => 'from-slate-500/10 to-slate-600/5 border-slate-200 dark:border-slate-800',
                        'teal' => 'from-teal-500/10 to-teal-600/5 border-teal-200 dark:border-teal-800',
                        'cyan' => 'from-cyan-500/10 to-cyan-600/5 border-cyan-200 dark:border-cyan-800',
                        'indigo' => 'from-indigo-500/10 to-indigo-600/5 border-indigo-200 dark:border-indigo-800',
                        'dark' => 'from-slate-500/10 to-slate-600/5 border-slate-200 dark:border-slate-800',
                    ];
                    $bgClass = $colorMap[$module['accent']] ?? $colorMap['blue'];
                ?>
                <<?= $tag; ?>
                    class="group card bg-gradient-to-br <?= $bgClass; ?> border-2 shadow-md hover:shadow-xl transition-all duration-300 <?= $isActive ? 'cursor-pointer hover:scale-105' : 'opacity-60 cursor-not-allowed'; ?>"
                    <?= $module['has_real_link'] ? 'href="' . htmlspecialchars($href) . '"' : ''; ?>
                    <?= !$module['has_real_link'] ? 'aria-disabled="true"' : ''; ?>
                >
                    <div class="card-body p-6 relative overflow-hidden">
                        <!-- Background accent -->
                        <div class="absolute -top-8 -right-8 w-32 h-32 bg-gradient-to-br opacity-5 rounded-full group-hover:scale-110 transition-transform duration-300"></div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4 relative z-10">
                            <div class="badge badge-lg <?= match($module['status']) {
                                'active' => 'badge-success',
                                'planned' => 'badge-warning',
                                'inactive' => 'badge-ghost',
                                default => 'badge-info',
                            } ?>">
                                <?= htmlspecialchars($module['status_label']); ?>
                            </div>
                        </div>

                        <!-- Icon -->
                        <div class="text-5xl mb-4 relative z-10">
                            <i class="<?= htmlspecialchars($module['icon_class']); ?> opacity-80 group-hover:opacity-100 transition-opacity"></i>
                        </div>

                        <!-- Content -->
                        <h2 class="card-title text-2xl mb-2 relative z-10 text-base-content">
                            <?= htmlspecialchars($module['short_name']); ?>
                        </h2>
                        <p class="text-base-content/70 text-sm leading-relaxed relative z-10">
                            <?= htmlspecialchars($module['description']); ?>
                        </p>

                        <!-- Action indicator -->
                        <?php if ($isActive): ?>
                        <div class="mt-4 pt-4 border-t border-base-300 relative z-10">
                            <div class="flex items-center gap-2 text-primary text-sm font-semibold group-hover:gap-3 transition-all">
                                <span>Acessar módulo</span>
                                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                        <?php elseif ($isPlanned): ?>
                        <div class="mt-4 pt-4 border-t border-base-300 relative z-10">
                            <p class="text-warning text-sm font-semibold">Em breve</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </<?= $tag; ?>>
            <?php endforeach; ?>
        </div>
    </div>
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
