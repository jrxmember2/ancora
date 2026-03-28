<section class="page-header">
    <h1 class="page-title">Unidades</h1>
    <p class="page-subtitle">Cadastro por condomínio, bloco e unidade, com proprietário e locatário reaproveitáveis.</p>
</section>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>
<div class="toolbar toolbar--stack-mobile mb-16"><div class="section-actions"><a class="btn btn-primary" href="<?= htmlspecialchars(base_url('/clientes/unidades/novo')); ?>"><i class="fa-solid fa-plus"></i> Nova unidade</a></div></div>
<div class="card mb-24"><div class="card-body">
<form method="get" action="<?= htmlspecialchars(base_url('/clientes/unidades')); ?>" class="form-grid form-grid-4">
<input class="form-control" type="search" name="q" placeholder="Buscar por condomínio, unidade, proprietário..." value="<?= htmlspecialchars($filters['q'] ?? ''); ?>">
<select class="form-select" name="condominium_id"><option value="">Todos os condomínios</option><?php foreach ($condominiumsDropdown as $cond): ?><option value="<?= (int) $cond['id']; ?>" <?= (int) ($filters['condominium_id'] ?? 0) === (int) $cond['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($cond['name']); ?></option><?php endforeach; ?></select>
<select class="form-select" name="unit_type_id"><option value="">Todos os tipos</option><?php foreach ($unitTypes as $type): ?><option value="<?= (int) $type['id']; ?>" <?= (int) ($filters['unit_type_id'] ?? 0) === (int) $type['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option><?php endforeach; ?></select>
<div class="section-actions"><button class="btn btn-primary" type="submit">Filtrar</button><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/unidades')); ?>">Limpar</a></div>
</form></div></div>
<div class="table-wrap"><table class="table"><thead><tr><th>Condomínio</th><th>Bloco</th><th>Unidade</th><th>Tipo</th><th>Proprietário</th><th>Locatário</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($items as $item): ?><tr><td><?= htmlspecialchars($item['condominium_name']); ?></td><td><?= htmlspecialchars($item['block_name'] ?? '—'); ?></td><td><strong><?= htmlspecialchars($item['unit_number']); ?></strong></td><td><?= htmlspecialchars($item['unit_type_name'] ?? '—'); ?></td><td><?= htmlspecialchars($item['owner_name'] ?? '—'); ?></td><td><?= htmlspecialchars($item['tenant_name'] ?? '—'); ?></td><td><a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/clientes/unidades/' . $item['id'] . '/editar')); ?>">Editar</a></td></tr><?php endforeach; ?>
</tbody></table></div>
