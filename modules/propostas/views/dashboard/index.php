<section class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Visão consolidada das propostas enviadas, follow-ups e validade comercial.</p>
</section>

<div class="toolbar">
    <form method="get" action="<?= htmlspecialchars(base_url('/dashboard')); ?>" class="inline-form">
        <label class="form-label mb-0" for="year">Ano</label>
        <select class="form-select" id="year" name="year" onchange="this.form.submit()">
            <?php foreach ($dashboard['years'] as $year): ?>
                <option value="<?= (int) $year; ?>" <?= (int) $dashboard['year'] === (int) $year ? 'selected' : ''; ?>><?= (int) $year; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <div class="segmented-control" id="chartTypeSwitch">
        <button class="segmented-control__btn is-active" type="button" data-chart-type="bar">Barras</button>
        <button class="segmented-control__btn" type="button" data-chart-type="line">Linha</button>
    </div>
</div>

<?php if (!empty($dashboard['alerts'])): ?>
    <button class="alert-banner alert-banner--danger alert-banner--blink mb-16 alert-banner--button" type="button" onclick="fetchDashboardDetails('alerts','all',<?= (int) $dashboard['year']; ?>).then(r => openDetailsModal('Itens em alerta de follow-up', r.records))">
        Atenção: existem propostas com follow-up faltando 5 dias ou menos, ou já vencidas. Clique para detalhar.
    </button>
<?php endif; ?>

<div class="grid-4 mb-24">
    <button class="kpi-card" type="button" onclick="fetchDashboardDetails('total_year','all',<?= (int) $dashboard['year']; ?>).then(r => openDetailsModal('Tudo que foi colocado na mesa no ano', r.records))">
        <div class="kpi-label">Valor Total no Ano</div>
        <p class="kpi-value"><?= htmlspecialchars(money_br($dashboard['total_amount'])); ?></p>
        <div class="kpi-meta">Inclui aprovadas, em negociação, aguardando e declinadas</div>
    </button>

    <button class="kpi-card" type="button" onclick="fetchDashboardDetails('closed_year','all',<?= (int) $dashboard['year']; ?>).then(r => openDetailsModal('Tudo que realmente foi fechado no ano', r.records))">
        <div class="kpi-label">Valor Total Fechado no Ano</div>
        <p class="kpi-value"><?= htmlspecialchars(money_br($dashboard['total_closed_amount'])); ?></p>
        <div class="kpi-meta">Somente propostas aprovadas</div>
    </button>

    <button class="kpi-card" type="button">
        <div class="kpi-label">Valor Declinado no Ano</div>
        <p class="kpi-value"><?= htmlspecialchars(money_br($dashboard['total_declined_amount'])); ?></p>
        <div class="kpi-meta">Quanto foi perdido entre as propostas declinadas</div>
    </button>

    <button class="kpi-card" type="button" onclick="fetchDashboardDetails('alerts','all',<?= (int) $dashboard['year']; ?>).then(r => openDetailsModal('Itens em alerta de follow-up', r.records))">
        <div class="kpi-label">Follow-ups em alerta</div>
        <p class="kpi-value"><?= count($dashboard['alerts']); ?></p>
        <div class="kpi-meta">Pendentes críticos</div>
    </button>
</div>

<div class="grid-2 mb-24">
    <button class="kpi-card" type="button" onclick="fetchDashboardDetails('validity_alerts','all',<?= (int) $dashboard['year']; ?>).then(r => openDetailsModal('Validades críticas', r.records))">
        <div class="kpi-label">Validades críticas</div>
        <p class="kpi-value"><?= count($dashboard['validity_alerts']); ?></p>
        <div class="kpi-meta">Expirando em até 3 dias</div>
    </button>
</div>

<div class="dashboard-grid mb-24">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Propostas enviadas por mês</h3></div>
        <div class="card-body chart-box"><canvas id="chartMonthly"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Tipos de serviços</h3></div>
        <div class="card-body chart-box"><canvas id="chartServices"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Administradoras / Síndicos</h3></div>
        <div class="card-body chart-box"><canvas id="chartAdmins"></canvas></div>
    </div>
</div>

<div class="grid-2">
    <?php if (!empty($dashboard['alerts'])): ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Itens em alerta de follow-up</h3></div>
        <div class="card-body">
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Nº</th><th>Cliente</th><th>Follow-up</th><th>Status</th><th>Ação</th></tr></thead>
                    <tbody>
                    <?php foreach ($dashboard['alerts'] as $item): ?>
                        <tr>
                            <td>#<?= (int) $item['id']; ?></td>
                            <td><?= htmlspecialchars($item['client_name']); ?></td>
                            <td><?= htmlspecialchars(date_br($item['followup_date'])); ?></td>
                            <td><?= htmlspecialchars($item['status_name']); ?></td>
                            <td><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $item['id'])); ?>">Abrir</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($dashboard['validity_alerts'])): ?>
    <div class="card">
        <div class="card-header"><h3 class="card-title">Validades em risco</h3></div>
        <div class="card-body">
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Nº</th><th>Cliente</th><th>Data-base</th><th>Limite</th><th>Ação</th></tr></thead>
                    <tbody>
                    <?php foreach ($dashboard['validity_alerts'] as $item): ?>
                        <tr>
                            <td>#<?= (int) $item['id']; ?></td>
                            <td><?= htmlspecialchars($item['client_name']); ?></td>
                            <td><?= htmlspecialchars(date_br($item['proposal_date'])); ?></td>
                            <td><?= htmlspecialchars(date_br($item['validity_limit_date'] ?? null)); ?></td>
                            <td><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $item['id'])); ?>">Abrir</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
window.APP_BASE_URL = <?= json_encode(rtrim(BASE_URL, '/')); ?>;
const dashboardData = <?= json_encode($dashboard, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
</script>
