<section class="page-header">
    <h1 class="page-title">Contatos reaproveitáveis</h1>
    <p class="page-subtitle">Síndicos, administradoras, proprietários, locatários e outros cadastros vinculáveis.</p>
</section>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>
<div class="toolbar toolbar--stack-mobile mb-16"><div class="section-actions"><a class="btn btn-primary" href="<?= htmlspecialchars(base_url('/clientes/contatos/novo')); ?>"><i class="fa-solid fa-plus"></i> Novo contato</a></div></div>
<div class="card mb-24"><div class="card-body">
<form method="get" action="<?= htmlspecialchars(base_url('/clientes/contatos')); ?>" class="form-grid form-grid-4">
<input class="form-control" type="search" name="q" placeholder="Buscar por nome, documento..." value="<?= htmlspecialchars($filters['q'] ?? ''); ?>">
<select class="form-select" name="entity_type"><option value="">PF e PJ</option><option value="pf" <?= ($filters['entity_type'] ?? '') === 'pf' ? 'selected' : ''; ?>>Pessoa física</option><option value="pj" <?= ($filters['entity_type'] ?? '') === 'pj' ? 'selected' : ''; ?>>Pessoa jurídica</option></select>
<select class="form-select" name="role_tag"><option value="">Todos os papéis</option><?php foreach (['sindico'=>'Síndico','administradora'=>'Administradora','proprietario'=>'Proprietário','locatario'=>'Locatário','outro'=>'Outro'] as $k=>$v): ?><option value="<?= $k; ?>" <?= ($filters['role_tag'] ?? '') === $k ? 'selected' : ''; ?>><?= $v; ?></option><?php endforeach; ?></select>
<select class="form-select" name="is_active"><option value="">Ativos e inativos</option><option value="1" <?= (string) ($filters['is_active'] ?? '') === '1' ? 'selected' : ''; ?>>Ativos</option><option value="0" <?= (string) ($filters['is_active'] ?? '') === '0' ? 'selected' : ''; ?>>Inativos</option></select>
<div class="section-actions"><button class="btn btn-primary" type="submit">Filtrar</button><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/contatos')); ?>">Limpar</a></div>
</form></div></div>
<div class="table-wrap"><table class="table"><thead><tr><th>Nome</th><th>Papel</th><th>Tipo</th><th>Documento</th><th>Status</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($items as $item): ?><tr><td><strong><?= htmlspecialchars($item['display_name']); ?></strong></td><td><?= htmlspecialchars($item['role_tag']); ?></td><td><?= htmlspecialchars(strtoupper($item['entity_type'])); ?></td><td><?= htmlspecialchars($item['cpf_cnpj'] ?? '—'); ?></td><td><span class="badge <?= (int) $item['is_active'] === 1 ? 'badge-success' : 'badge-danger'; ?>"><?= (int) $item['is_active'] === 1 ? 'Ativo' : 'Inativo'; ?></span></td><td><a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/clientes/contatos/' . $item['id'] . '/editar')); ?>">Editar</a></td></tr><?php endforeach; ?>
</tbody></table></div>
