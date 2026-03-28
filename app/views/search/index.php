<section class="page-header">
    <h1 class="page-title">Busca global</h1>
    <p class="page-subtitle">Pesquise propostas, usuários e logs a partir de um único ponto.</p>
</section>

<div class="card mb-24">
    <div class="card-body">
        <form class="search-hero" method="get" action="<?= htmlspecialchars(base_url('/busca')); ?>">
            <input class="search-hero__input" type="search" name="q" value="<?= htmlspecialchars($searchTerm); ?>" placeholder="Digite nome do cliente, nº da proposta, e-mail, ação do log..." required>
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>
    </div>
</div>

<?php if ($searchTerm === ''): ?>
    <div class="empty-state">
        <div class="empty-state__icon"><i class="fa-solid fa-magnifying-glass"></i></div>
        <h3 class="empty-state__title">Comece pela busca</h3>
        <p class="empty-state__text">Use a caixa acima para localizar propostas, usuários e registros de auditoria.</p>
    </div>
<?php else: ?>
    <div class="search-section-grid">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Propostas (<?= count($results['propostas']); ?>)</h3></div>
            <div class="card-body">
                <?php if (empty($results['propostas'])): ?>
                    <p class="text-muted mb-0">Nenhuma proposta encontrada.</p>
                <?php else: ?>
                    <div class="search-list">
                        <?php foreach ($results['propostas'] as $item): ?>
                            <a class="search-list__item" href="<?= htmlspecialchars(base_url('/propostas/' . $item['id'])); ?>">
                                <strong>#<?= (int) $item['id']; ?> · <?= htmlspecialchars($item['client_name']); ?></strong>
                                <span><?= htmlspecialchars($item['administradora_name']); ?> · <?= htmlspecialchars($item['service_name']); ?></span>
                                <span><?= htmlspecialchars(date_br($item['proposal_date'])); ?> · <?= htmlspecialchars(money_br((float) $item['proposal_total'])); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (is_superadmin()): ?>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Usuários (<?= count($results['usuarios']); ?>)</h3></div>
            <div class="card-body">
                <?php if (empty($results['usuarios'])): ?>
                    <p class="text-muted mb-0">Nenhum usuário encontrado.</p>
                <?php else: ?>
                    <div class="search-list">
                        <?php foreach ($results['usuarios'] as $item): ?>
                            <div class="search-list__item">
                                <strong><?= htmlspecialchars($item['name']); ?></strong>
                                <span><?= htmlspecialchars($item['email']); ?></span>
                                <span><?= htmlspecialchars($item['role']); ?> · <?= (int) $item['is_active'] === 1 ? 'Ativo' : 'Inativo'; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Logs (<?= count($results['logs']); ?>)</h3></div>
            <div class="card-body">
                <?php if (empty($results['logs'])): ?>
                    <p class="text-muted mb-0">Nenhum log encontrado.</p>
                <?php else: ?>
                    <div class="search-list">
                        <?php foreach ($results['logs'] as $item): ?>
                            <div class="search-list__item">
                                <strong><?= htmlspecialchars($item['action']); ?></strong>
                                <span><?= htmlspecialchars($item['user_email']); ?></span>
                                <span><?= htmlspecialchars(date('d/m/Y H:i', strtotime($item['created_at']))); ?> · <?= htmlspecialchars((string) $item['details']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
