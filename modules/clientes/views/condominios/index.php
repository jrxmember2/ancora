<section class="page-header">
    <h1 class="page-title">Condomínios</h1>
    <p class="page-subtitle">Cadastro estrutural do ramo condominial com síndico, administradora, blocos e vínculo de unidades.</p>
</section>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>
<div class="toolbar toolbar--stack-mobile mb-16"><div class="section-actions"><a class="btn btn-primary" href="<?= htmlspecialchars(base_url('/clientes/condominios/novo')); ?>"><i class="fa-solid fa-plus"></i> Novo condomínio</a></div></div>
<div class="card mb-24"><div class="card-body">
<form method="get" action="<?= htmlspecialchars(base_url('/clientes/condominios')); ?>" class="form-grid form-grid-4">
<input class="form-control" type="search" name="q" placeholder="Buscar por nome ou CNPJ..." value="<?= htmlspecialchars($filters['q'] ?? ''); ?>">
<select class="form-select" name="type_id"><option value="">Todos os tipos</option><?php foreach ($condominiumTypes as $type): ?><option value="<?= (int) $type['id']; ?>" <?= (int) ($filters['type_id'] ?? 0) === (int) $type['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option><?php endforeach; ?></select>
<select class="form-select" name="syndico_entity_id"><option value="">Todos os síndicos</option><?php foreach ($syndics as $entity): ?><option value="<?= (int) $entity['id']; ?>" <?= (int) ($filters['syndico_entity_id'] ?? 0) === (int) $entity['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($entity['display_name']); ?></option><?php endforeach; ?></select>
<select class="form-select" name="is_active"><option value="">Ativos e inativos</option><option value="1" <?= (string) ($filters['is_active'] ?? '') === '1' ? 'selected' : ''; ?>>Ativos</option><option value="0" <?= (string) ($filters['is_active'] ?? '') === '0' ? 'selected' : ''; ?>>Inativos</option></select>
<div class="section-actions"><button class="btn btn-primary" type="submit">Filtrar</button><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/condominios')); ?>">Limpar</a></div>
</form></div></div>
<div class="table-wrap"><table class="table"><thead><tr><th>Condomínio</th><th>Tipo</th><th>Síndico</th><th>Blocos</th><th>Unidades</th><th>Status</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($items as $item): ?><tr><td><strong><?= htmlspecialchars($item['name']); ?></strong><br><span class="text-muted"><?= htmlspecialchars($item['cnpj'] ?? ''); ?></span></td><td><?= htmlspecialchars($item['condominium_type_name'] ?? '—'); ?></td><td><?= htmlspecialchars($item['syndico_name'] ?? '—'); ?></td><td><?= (int) ($item['blocks_total'] ?? 0); ?></td><td><?= (int) ($item['units_total'] ?? 0); ?></td><td><span class="badge <?= (int) $item['is_active'] === 1 ? 'badge-success' : 'badge-danger'; ?>"><?= (int) $item['is_active'] === 1 ? 'Ativo' : 'Inativo'; ?></span></td><td><a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/clientes/condominios/' . $item['id'] . '/editar')); ?>">Editar</a></td></tr><?php endforeach; ?>
</tbody></table></div>
