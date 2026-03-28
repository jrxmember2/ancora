<?php
$owner = $owner ?? null;
$tenant = $tenant ?? null;
$selectedCondominiumId = (int) ($item['condominium_id'] ?? 0);
$selectedBlocks = $selectedCondominiumId ? ClientBlock::byCondominium($selectedCondominiumId) : [];
?>
<section class="page-header">
    <h1 class="page-title"><?= $item ? 'Editar unidade' : 'Nova unidade'; ?></h1>
    <p class="page-subtitle">Vincule a unidade ao condomínio e defina proprietário e locatário.</p>
</section>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>
<div class="toolbar toolbar--stack-mobile mb-16"><div class="section-actions"><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/unidades')); ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a></div></div>
<form method="post" action="<?= htmlspecialchars($item ? base_url('/clientes/unidades/' . $item['id'] . '/update') : base_url('/clientes/unidades/store')); ?>" class="stack-lg clients-form">
<?= csrf_field(); ?>
<div class="card"><div class="card-header"><h3 class="card-title">Dados da unidade</h3></div><div class="card-body form-grid form-grid-4">
<label class="form-field"><span>Condomínio</span><select class="form-select" name="condominium_id" required><option value="">Selecione</option><?php foreach ($condominiumsDropdown as $cond): ?><option value="<?= (int) $cond['id']; ?>" <?= $selectedCondominiumId === (int) $cond['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($cond['name']); ?></option><?php endforeach; ?></select></label>
<label class="form-field"><span>Bloco / torre</span><select class="form-select" name="block_id"><option value="">Sem bloco</option><?php foreach ($selectedBlocks as $block): ?><option value="<?= (int) $block['id']; ?>" <?= (int) ($item['block_id'] ?? 0) === (int) $block['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($block['name']); ?></option><?php endforeach; ?></select></label>
<label class="form-field"><span>Tipo da unidade</span><select class="form-select" name="unit_type_id"><option value="">Selecione</option><?php foreach ($unitTypes as $type): ?><option value="<?= (int) $type['id']; ?>" <?= (int) ($item['unit_type_id'] ?? 0) === (int) $type['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option><?php endforeach; ?></select></label>
<label class="form-field"><span>Número da unidade</span><input class="form-control" type="text" name="unit_number" value="<?= htmlspecialchars($item['unit_number'] ?? ''); ?>" required></label>
</div></div>

<div class="dashboard-grid dashboard-grid--clients-detail">
<div class="card"><div class="card-header"><h3 class="card-title">Proprietário</h3></div><div class="card-body stack-md">
<label class="form-field"><span>Modo</span><select class="form-select" name="owner_mode" data-owner-mode><option value="existing" <?= $owner ? 'selected' : ''; ?>>Selecionar existente</option><option value="new" <?= !$owner ? 'selected' : ''; ?>>Cadastrar novo</option></select></label>
<div class="owner-existing">
<label class="form-field"><span>Cadastro existente</span><select class="form-select" name="owner_entity_id"><option value="">Selecione</option><?php foreach ($entitiesAll as $entity): ?><option value="<?= (int) $entity['id']; ?>" <?= (int) ($item['owner_entity_id'] ?? 0) === (int) $entity['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($entity['display_name'] . ' · ' . strtoupper($entity['entity_type'])); ?></option><?php endforeach; ?></select></label>
<?php if ($owner): ?><div class="inline-entity-resume"><strong><?= htmlspecialchars($owner['display_name']); ?></strong><span><?= htmlspecialchars($owner['cpf_cnpj'] ?? ''); ?></span></div><?php endif; ?>
</div>
<div class="owner-new form-grid form-grid-2">
<label class="form-field"><span>Tipo</span><select class="form-select" name="owner_entity_type"><option value="pf">PF</option><option value="pj">PJ</option></select></label>
<label class="form-field"><span>Nome / razão social</span><input class="form-control" type="text" name="owner_display_name"></label>
<label class="form-field"><span>Nome fantasia / complementar</span><input class="form-control" type="text" name="owner_legal_name"></label>
<label class="form-field"><span>CPF / CNPJ</span><input class="form-control" type="text" name="owner_cpf_cnpj"></label>
<div class="repeater form-grid-full" data-repeater="owner_phones"><div class="repeater__header"><strong>Telefones</strong><button class="btn btn-ghost" type="button" data-repeater-add="owner_phones">Adicionar</button></div><div data-repeater-items="owner_phones"><div class="repeater__row"><input class="form-control" type="text" name="owner_phones[0][label]" placeholder="Rótulo"><input class="form-control" type="text" name="owner_phones[0][number]" placeholder="Número"><button class="btn btn-danger" type="button" data-repeater-remove>Remover</button></div></div></div>
<div class="repeater form-grid-full" data-repeater="owner_emails"><div class="repeater__header"><strong>E-mails</strong><button class="btn btn-ghost" type="button" data-repeater-add="owner_emails">Adicionar</button></div><div data-repeater-items="owner_emails"><div class="repeater__row"><input class="form-control" type="text" name="owner_emails[0][label]" placeholder="Rótulo"><input class="form-control" type="email" name="owner_emails[0][email]" placeholder="E-mail"><button class="btn btn-danger" type="button" data-repeater-remove>Remover</button></div></div></div>
<?php foreach (['street'=>'Endereço de cobrança','number'=>'Número','complement'=>'Complemento','neighborhood'=>'Bairro','city'=>'Cidade','state'=>'Estado','zip'=>'CEP'] as $field=>$label): ?><label class="form-field"><span><?= $label; ?></span><input class="form-control" type="text" name="owner_billing_address_<?= $field; ?>"></label><?php endforeach; ?>
<label class="form-field form-grid-full"><span>Observação do proprietário</span><textarea class="form-control" name="owner_notes" rows="3"><?= htmlspecialchars($item['owner_notes'] ?? ''); ?></textarea></label>
</div>
</div></div>

<div class="card"><div class="card-header"><h3 class="card-title">Locatário</h3></div><div class="card-body stack-md">
<label class="form-field"><span>Modo</span><select class="form-select" name="tenant_mode" data-tenant-mode><option value="existing" <?= $tenant ? 'selected' : ''; ?>>Selecionar existente</option><option value="new" <?= !$tenant ? 'selected' : ''; ?>>Cadastrar novo</option></select></label>
<div class="tenant-existing">
<label class="form-field"><span>Cadastro existente</span><select class="form-select" name="tenant_entity_id"><option value="">Selecione</option><?php foreach ($entitiesAll as $entity): ?><option value="<?= (int) $entity['id']; ?>" <?= (int) ($item['tenant_entity_id'] ?? 0) === (int) $entity['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($entity['display_name'] . ' · ' . strtoupper($entity['entity_type'])); ?></option><?php endforeach; ?></select></label>
<?php if ($tenant): ?><div class="inline-entity-resume"><strong><?= htmlspecialchars($tenant['display_name']); ?></strong><span><?= htmlspecialchars($tenant['cpf_cnpj'] ?? ''); ?></span></div><?php endif; ?>
</div>
<div class="tenant-new form-grid form-grid-2">
<label class="form-field"><span>Tipo</span><select class="form-select" name="tenant_entity_type"><option value="pf">PF</option><option value="pj">PJ</option></select></label>
<label class="form-field"><span>Nome / razão social</span><input class="form-control" type="text" name="tenant_display_name"></label>
<label class="form-field"><span>Nome fantasia / complementar</span><input class="form-control" type="text" name="tenant_legal_name"></label>
<label class="form-field"><span>CPF / CNPJ</span><input class="form-control" type="text" name="tenant_cpf_cnpj"></label>
<div class="repeater form-grid-full" data-repeater="tenant_phones"><div class="repeater__header"><strong>Telefones</strong><button class="btn btn-ghost" type="button" data-repeater-add="tenant_phones">Adicionar</button></div><div data-repeater-items="tenant_phones"><div class="repeater__row"><input class="form-control" type="text" name="tenant_phones[0][label]" placeholder="Rótulo"><input class="form-control" type="text" name="tenant_phones[0][number]" placeholder="Número"><button class="btn btn-danger" type="button" data-repeater-remove>Remover</button></div></div></div>
<div class="repeater form-grid-full" data-repeater="tenant_emails"><div class="repeater__header"><strong>E-mails</strong><button class="btn btn-ghost" type="button" data-repeater-add="tenant_emails">Adicionar</button></div><div data-repeater-items="tenant_emails"><div class="repeater__row"><input class="form-control" type="text" name="tenant_emails[0][label]" placeholder="Rótulo"><input class="form-control" type="email" name="tenant_emails[0][email]" placeholder="E-mail"><button class="btn btn-danger" type="button" data-repeater-remove>Remover</button></div></div></div>
<?php foreach (['street'=>'Endereço de cobrança','number'=>'Número','complement'=>'Complemento','neighborhood'=>'Bairro','city'=>'Cidade','state'=>'Estado','zip'=>'CEP'] as $field=>$label): ?><label class="form-field"><span><?= $label; ?></span><input class="form-control" type="text" name="tenant_billing_address_<?= $field; ?>"></label><?php endforeach; ?>
<label class="form-field form-grid-full"><span>Observação do locatário</span><textarea class="form-control" name="tenant_notes" rows="3"><?= htmlspecialchars($item['tenant_notes'] ?? ''); ?></textarea></label>
</div>
</div></div>
</div>

<?php if ($item): ?><div class="card"><div class="card-header"><h3 class="card-title">Timeline</h3></div><div class="card-body stack-md"><label class="form-field"><span>Novo registro</span><textarea class="form-control" name="timeline_note" rows="3"></textarea></label><div class="timeline-list"><?php foreach ($timeline as $event): ?><div class="timeline-item"><strong><?= htmlspecialchars(date('d/m/Y H:i', strtotime((string) $event['created_at']))); ?></strong><p><?= nl2br(htmlspecialchars($event['note'])); ?></p><span><?= htmlspecialchars($event['user_email']); ?></span></div><?php endforeach; ?></div></div></div><?php endif; ?>
<div class="section-actions"><button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Salvar unidade</button><?php if ($item): ?><span class="text-muted">Exclusão disponível pela listagem do módulo.</span><?php endif; ?></div>
</form>
