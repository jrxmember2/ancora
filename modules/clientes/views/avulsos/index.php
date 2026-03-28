<section class="page-header">
    <h1 class="page-title">Clientes avulsos</h1>
    <p class="page-subtitle">Cadastros PF e PJ do escritório, fora da estrutura condominial.</p>
</section>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>
<div class="toolbar toolbar--stack-mobile mb-16">
    <div class="section-actions">
        <a class="btn btn-primary" href="<?= htmlspecialchars(base_url('/clientes/avulsos/novo')); ?>"><i class="fa-solid fa-plus"></i> Novo cliente avulso</a>
    </div>
</div>
<div class="card mb-24"><div class="card-body">
    <form method="get" action="<?= htmlspecialchars(base_url('/clientes/avulsos')); ?>" class="form-grid form-grid-4">
        <input class="form-control" type="search" name="q" placeholder="Buscar por nome, documento..." value="<?= htmlspecialchars($filters['q'] ?? ''); ?>">
        <select class="form-select" name="entity_type"><option value="">PF e PJ</option><option value="pf" <?= ($filters['entity_type'] ?? '') === 'pf' ? 'selected' : ''; ?>>Pessoa física</option><option value="pj" <?= ($filters['entity_type'] ?? '') === 'pj' ? 'selected' : ''; ?>>Pessoa jurídica</option></select>
        <select class="form-select" name="is_active"><option value="">Ativos e inativos</option><option value="1" <?= (string) ($filters['is_active'] ?? '') === '1' ? 'selected' : ''; ?>>Ativos</option><option value="0" <?= (string) ($filters['is_active'] ?? '') === '0' ? 'selected' : ''; ?>>Inativos</option></select>
        <div class="section-actions"><button class="btn btn-primary" type="submit">Filtrar</button><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/avulsos')); ?>">Limpar</a></div>
    </form>
</div></div>
<?php if (!$items): ?><div class="empty-state"><div class="empty-state__icon"><i class="fa-solid fa-users"></i></div><h3 class="empty-state__title">Nenhum cliente avulso encontrado</h3></div><?php else: ?>
<div class="table-wrap"><table class="table"><thead><tr><th>Nome</th><th>Tipo</th><th>Documento</th><th>Status</th><th>Contatos</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($items as $item): ?>
<tr>
<td><strong><?= htmlspecialchars($item['display_name']); ?></strong><br><span class="text-muted"><?= htmlspecialchars($item['legal_name'] ?? ''); ?></span></td>
<td><?= htmlspecialchars(strtoupper($item['entity_type'])); ?></td>
<td><?= htmlspecialchars($item['cpf_cnpj'] ?? '—'); ?></td>
<td><span class="badge <?= (int) $item['is_active'] === 1 ? 'badge-success' : 'badge-danger'; ?>"><?= (int) $item['is_active'] === 1 ? 'Ativo' : 'Inativo'; ?></span></td>
<td><?= htmlspecialchars($item['phones'][0]['number'] ?? $item['emails'][0]['email'] ?? '—'); ?></td>
<td><div class="actions"><a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/clientes/avulsos/' . $item['id'] . '/editar')); ?>">Editar</a></div></td>
</tr>
<?php endforeach; ?>
</tbody></table></div><?php endif; ?>
