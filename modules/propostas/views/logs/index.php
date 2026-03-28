<section class="page-header">
    <h1 class="page-title">Logs do Sistema</h1>
    <p class="page-subtitle">Auditoria cronológica com busca, filtros e paginação.</p>
</section>

<div class="card mb-24">
    <div class="card-header"><h3 class="card-title">Filtros</h3></div>
    <div class="card-body">
        <form method="get" action="<?= htmlspecialchars(base_url('/logs')); ?>" class="form-grid form-grid-3">
            <input class="form-control" type="search" name="q" value="<?= htmlspecialchars($filters['q']); ?>" placeholder="Buscar por usuário, ação ou detalhes...">
            <select class="form-select" name="action">
                <option value="">Todas as ações</option>
                <?php foreach ($actions as $action): ?>
                    <option value="<?= htmlspecialchars($action); ?>" <?= $filters['action'] === $action ? 'selected' : ''; ?>><?= htmlspecialchars($action); ?></option>
                <?php endforeach; ?>
            </select>
            <select class="form-select" name="user_email">
                <option value="">Todos os usuários</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user); ?>" <?= $filters['user_email'] === $user ? 'selected' : ''; ?>><?= htmlspecialchars($user); ?></option>
                <?php endforeach; ?>
            </select>
            <input class="form-control" type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']); ?>">
            <input class="form-control" type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']); ?>">
            <select class="form-select" name="per_page">
                <?php foreach ([25, 50, 100, 200] as $size): ?>
                    <option value="<?= $size; ?>" <?= (int) $pagination['per_page'] === $size ? 'selected' : ''; ?>><?= $size; ?> por página</option>
                <?php endforeach; ?>
            </select>
            <div class="section-actions">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter"></i> Aplicar filtros</button>
                <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/logs')); ?>"><i class="fa-solid fa-rotate-left"></i> Limpar</a>
            </div>
        </form>
    </div>
</div>

<?php if (empty($logs)): ?>
    <div class="empty-state">
        <div class="empty-state__icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <h3 class="empty-state__title">Nenhum log encontrado</h3>
        <p class="empty-state__text">Ajuste os filtros para localizar os registros desejados.</p>
    </div>
<?php else: ?>
<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Data/Hora</th>
                <th>Usuário</th>
                <th>Ação</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($log['created_at']))); ?></td>
                    <td><?= htmlspecialchars($log['user_email']); ?></td>
                    <td><span class="badge badge-neutral"><?= htmlspecialchars($log['action']); ?></span></td>
                    <td><?= htmlspecialchars((string) $log['details']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="pagination-bar">
    <div class="pagination-bar__info">Página <?= (int) $pagination['page']; ?> de <?= (int) $pagination['pages']; ?> · <?= (int) $pagination['total']; ?> registro(s)</div>
    <div class="pagination-bar__links">
        <?php if ((int) $pagination['page'] > 1): ?>
            <a class="btn btn-ghost" href="<?= htmlspecialchars(url_with_query('/logs', ['page' => $pagination['page'] - 1])); ?>"><i class="fa-solid fa-chevron-left"></i> Anterior</a>
        <?php endif; ?>
        <?php if ((int) $pagination['page'] < (int) $pagination['pages']): ?>
            <a class="btn btn-ghost" href="<?= htmlspecialchars(url_with_query('/logs', ['page' => $pagination['page'] + 1])); ?>">Próxima <i class="fa-solid fa-chevron-right"></i></a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
