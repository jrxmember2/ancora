<section class="page-header">
    <h1 class="page-title">Módulo Clientes</h1>
    <p class="page-subtitle">Base central do HUB para clientes avulsos, contatos reaproveitáveis, condomínios e unidades.</p>
</section>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>

<div class="stats-grid clients-stats-grid mb-24">
    <article class="stat-card"><span>Total de cadastros</span><strong><?= (int) ($entityCounts['total'] ?? 0); ?></strong><small>Entidades reutilizáveis</small></article>
    <article class="stat-card"><span>Clientes avulsos</span><strong><?= (int) ($entityCounts['avulsos_total'] ?? 0); ?></strong><small>PF + PJ</small></article>
    <article class="stat-card"><span>Condomínios</span><strong><?= (int) ($condominiumCounts['total'] ?? 0); ?></strong><small><?= (int) ($condominiumCounts['with_blocks_total'] ?? 0); ?> com blocos</small></article>
    <article class="stat-card"><span>Unidades</span><strong><?= (int) ($unitCounts['total'] ?? 0); ?></strong><small><?= (int) ($unitCounts['rented_total'] ?? 0); ?> com locatário</small></article>
</div>

<div class="dashboard-grid dashboard-grid--clients">
    <div class="card">
        <div class="card-header card-header--split">
            <div>
                <h3 class="card-title">Fluxo rápido</h3>
                <p class="text-muted">Entradas principais do módulo.</p>
            </div>
        </div>
        <div class="card-body action-grid">
            <a class="action-tile" href="<?= htmlspecialchars(base_url('/clientes/avulsos/novo')); ?>"><i class="fa-solid fa-user-plus"></i><strong>Novo cliente avulso</strong><span>PF ou PJ com anexos e timeline.</span></a>
            <a class="action-tile" href="<?= htmlspecialchars(base_url('/clientes/contatos/novo')); ?>"><i class="fa-solid fa-id-card"></i><strong>Novo contato</strong><span>Síndico, administradora, proprietário, locatário ou outro.</span></a>
            <a class="action-tile" href="<?= htmlspecialchars(base_url('/clientes/condominios/novo')); ?>"><i class="fa-solid fa-building"></i><strong>Novo condomínio</strong><span>Estrutura condominial com síndico e blocos.</span></a>
            <a class="action-tile" href="<?= htmlspecialchars(base_url('/clientes/unidades/novo')); ?>"><i class="fa-solid fa-door-open"></i><strong>Nova unidade</strong><span>Vínculo com proprietário e locatário.</span></a>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Cadastros recentes</h3></div>
        <div class="card-body">
            <div class="mini-list">
                <?php foreach ($recentEntities as $entity): ?>
                    <div class="mini-list__item">
                        <div>
                            <strong><?= htmlspecialchars($entity['display_name']); ?></strong>
                            <span><?= htmlspecialchars(strtoupper($entity['profile_scope']) . ' · ' . strtoupper($entity['entity_type'])); ?></span>
                        </div>
                        <span class="badge badge-neutral"><?= htmlspecialchars($entity['role_tag']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Condomínios recentes</h3></div>
        <div class="card-body">
            <div class="mini-list">
                <?php foreach ($recentCondominiums as $condominium): ?>
                    <div class="mini-list__item">
                        <div>
                            <strong><?= htmlspecialchars($condominium['name']); ?></strong>
                            <span><?= htmlspecialchars($condominium['condominium_type_name'] ?? 'Sem tipo'); ?></span>
                        </div>
                        <span class="badge badge-neutral"><?= (int) ($condominium['units_total'] ?? 0); ?> unidades</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
