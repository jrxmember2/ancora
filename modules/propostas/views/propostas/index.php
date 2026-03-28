<section class="page-header">
    <h1 class="page-title">Controle de Propostas</h1>
    <p class="page-subtitle">Listagem inteligente com filtros, busca, paginação e exportação.</p>
</section>

<div class="toolbar toolbar--stack-mobile">
    <div class="section-actions">
        <a class="btn btn-primary" href="<?= htmlspecialchars(base_url('/propostas/nova')); ?>"><i class="fa-solid fa-plus"></i> Nova proposta</a>
        <a class="btn btn-secondary" href="<?= htmlspecialchars(url_with_query('/propostas/export/csv', [], ['page'])); ?>"><i class="fa-solid fa-file-csv"></i> Exportar Excel (CSV)</a>
    </div>
    <div class="toolbar__meta text-muted">
        <?= (int) $pagination['total']; ?> registro(s) · Total filtrado: <?= htmlspecialchars(money_br($pagination['totals']['proposal_total'])); ?> · Fechado: <?= htmlspecialchars(money_br($pagination['totals']['closed_total'])); ?>
    </div>
</div>

<div class="card mb-24">
    <div class="card-header"><h3 class="card-title">Filtros</h3></div>
    <div class="card-body">
        <form method="get" action="<?= htmlspecialchars(base_url('/propostas')); ?>" class="form-grid form-grid-3">
            <input class="form-control" type="search" name="q" value="<?= htmlspecialchars($filters['q']); ?>" placeholder="Buscar por nº, cliente, solicitante, e-mail...">

            <select class="form-select" name="administradora_id">
                <option value="">Todas as administradoras</option>
                <?php foreach ($filterOptions['administradoras'] as $item): ?>
                    <option value="<?= (int) $item['id']; ?>" <?= (int) $filters['administradora_id'] === (int) $item['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <select class="form-select" name="service_id">
                <option value="">Todos os serviços</option>
                <?php foreach ($filterOptions['servicos'] as $item): ?>
                    <option value="<?= (int) $item['id']; ?>" <?= (int) $filters['service_id'] === (int) $item['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <select class="form-select" name="response_status_id">
                <option value="">Todos os status</option>
                <?php foreach ($filterOptions['statusRetorno'] as $item): ?>
                    <option value="<?= (int) $item['id']; ?>" <?= (int) $filters['response_status_id'] === (int) $item['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <select class="form-select" name="send_method_id">
                <option value="">Todas as formas de envio</option>
                <?php foreach ($filterOptions['formasEnvio'] as $item): ?>
                    <option value="<?= (int) $item['id']; ?>" <?= (int) $filters['send_method_id'] === (int) $item['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <select class="form-select" name="year">
                <option value="">Todos os anos</option>
                <?php foreach ($filterOptions['years'] as $year): ?>
                    <option value="<?= (int) $year; ?>" <?= (int) $filters['year'] === (int) $year ? 'selected' : ''; ?>><?= (int) $year; ?></option>
                <?php endforeach; ?>
            </select>

            <input class="form-control" type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']); ?>">
            <input class="form-control" type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']); ?>">
            <select class="form-select" name="per_page">
                <?php foreach ([15, 30, 50, 100] as $size): ?>
                    <option value="<?= $size; ?>" <?= (int) $pagination['per_page'] === $size ? 'selected' : ''; ?>><?= $size; ?> por página</option>
                <?php endforeach; ?>
            </select>

            <div class="section-actions">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter"></i> Aplicar filtros</button>
                <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas')); ?>"><i class="fa-solid fa-rotate-left"></i> Limpar</a>
            </div>
        </form>
    </div>
</div>

<?php if (empty($propostas)): ?>
    <div class="empty-state">
        <div class="empty-state__icon"><i class="fa-solid fa-folder-open"></i></div>
        <h3 class="empty-state__title">Nenhuma proposta encontrada</h3>
        <p class="empty-state__text">Ajuste os filtros ou cadastre uma nova proposta.</p>
    </div>
<?php else: ?>
<div class="table-wrap">
    <table class="table">
        <thead>
    <tr>
        <th>Nº</th>
        <th>Data</th>
        <th>Cliente</th>
        <th>Indicação</th>
        <th>Administradora</th>
        <th>Serviço</th>
        <th>Forma de Envio</th>
        <th>Status</th>
        <th>Valor</th>
        <th>Anexos</th>
        <th>Ações</th>
    </tr>
</thead>
        <tbody>
            <?php foreach ($propostas as $item): ?>
                <tr>
    <td><?= htmlspecialchars($item['proposal_code'] ?? ('#' . (int) $item['id'])); ?></td>
    <td><?= htmlspecialchars(date_br($item['proposal_date'])); ?></td>
    <td><?= htmlspecialchars($item['client_name']); ?></td>
    <td>
        <?= (int) ($item['has_referral'] ?? 0) === 1
            ? htmlspecialchars($item['referral_name'] ?: 'Sim')
            : '—'; ?>
    </td>
    <td><?= htmlspecialchars($item['administradora_name']); ?></td>
    <td><?= htmlspecialchars($item['service_name']); ?></td>
    <td>
        <span class="icon-method">
            <i class="<?= htmlspecialchars($item['icon_class']); ?>" style="color: <?= htmlspecialchars($item['send_method_color']); ?>"></i>
            <?= htmlspecialchars($item['send_method_name']); ?>
        </span>
    </td>
    <td><span class="badge" style="border-color: <?= htmlspecialchars($item['status_color']); ?>; color: <?= htmlspecialchars($item['status_color']); ?>"><?= htmlspecialchars($item['status_name']); ?></span></td>
    <td><?= htmlspecialchars(money_br((float) $item['proposal_total'])); ?></td>
    <td><span class="badge badge-neutral"><i class="fa-solid fa-paperclip"></i> <?= (int) ($item['attachment_count'] ?? 0); ?></span></td>
    <td>
        <div class="actions">
            <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $item['id'])); ?>">Visualizar</a>
            <a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/propostas/' . $item['id'] . '/editar')); ?>">Editar</a>
            <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $item['id'] . '/imprimir')); ?>" target="_blank" rel="noopener">PDF</a>
            <form id="delete-form-<?= (int) $item['id']; ?>" method="post" action="<?= htmlspecialchars(base_url('/propostas/' . $item['id'] . '/delete')); ?>">
                <?= csrf_field(); ?>
                <button type="button" class="btn btn-danger" data-delete-confirm data-item-label="a proposta #<?= (int) $item['id']; ?>" data-form-target="#delete-form-<?= (int) $item['id']; ?>">Excluir</button>
            </form>
        </div>
    </td>
</tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="pagination-bar">
    <div class="pagination-bar__info">
        Página <?= (int) $pagination['page']; ?> de <?= (int) $pagination['pages']; ?>
    </div>
    <div class="pagination-bar__links">
        <?php if ((int) $pagination['page'] > 1): ?>
            <a class="btn btn-ghost" href="<?= htmlspecialchars(url_with_query('/propostas', ['page' => $pagination['page'] - 1])); ?>"><i class="fa-solid fa-chevron-left"></i> Anterior</a>
        <?php endif; ?>
        <?php if ((int) $pagination['page'] < (int) $pagination['pages']): ?>
            <a class="btn btn-ghost" href="<?= htmlspecialchars(url_with_query('/propostas', ['page' => $pagination['page'] + 1])); ?>">Próxima <i class="fa-solid fa-chevron-right"></i></a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
