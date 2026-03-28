<?php
$address = $item['address'] ?? [];
$blocks = $item['blocks'] ?? [['name' => '']];
?>
<section class="page-header">
    <h1 class="page-title"><?= $item ? 'Editar condomínio' : 'Novo condomínio'; ?></h1>
    <p class="page-subtitle">Estrutura base para relacionamento com blocos, unidades e síndico.</p>
</section>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>
<div class="toolbar toolbar--stack-mobile mb-16"><div class="section-actions"><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/condominios')); ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a></div></div>
<form method="post" action="<?= htmlspecialchars($item ? base_url('/clientes/condominios/' . $item['id'] . '/update') : base_url('/clientes/condominios/store')); ?>" enctype="multipart/form-data" class="stack-lg clients-form">
<?= csrf_field(); ?>
<div class="card"><div class="card-header"><h3 class="card-title">Dados do condomínio</h3></div><div class="card-body form-grid form-grid-4">
<label class="form-field"><span>Data de cadastro</span><input class="form-control" type="text" value="<?= htmlspecialchars($item['created_at'] ?? date('Y-m-d H:i:s')); ?>" readonly></label>
<label class="form-field"><span>Nome</span><input class="form-control" type="text" name="name" value="<?= htmlspecialchars($item['name'] ?? ''); ?>" required></label>
<label class="form-field"><span>Tipo</span><select class="form-select" name="condominium_type_id"><option value="">Selecione</option><?php foreach ($condominiumTypes as $type): ?><option value="<?= (int) $type['id']; ?>" <?= (int) ($item['condominium_type_id'] ?? 0) === (int) $type['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($type['name']); ?></option><?php endforeach; ?></select><small class="text-muted">Novos tipos podem ser criados em Tipos.</small></label>
<label class="form-field"><span>Possui blocos?</span><select class="form-select" name="has_blocks" data-has-blocks-toggle><option value="0" <?= empty($item['has_blocks']) ? 'selected' : ''; ?>>Não</option><option value="1" <?= !empty($item['has_blocks']) ? 'selected' : ''; ?>>Sim</option></select></label>
<label class="form-field"><span>CNPJ</span><input class="form-control" type="text" name="cnpj" value="<?= htmlspecialchars($item['cnpj'] ?? ''); ?>"></label>
<label class="form-field"><span>CNAE</span><input class="form-control" type="text" name="cnae" value="<?= htmlspecialchars($item['cnae'] ?? ''); ?>"></label>
<label class="form-field"><span>Inscrição estadual</span><input class="form-control" type="text" name="state_registration" value="<?= htmlspecialchars($item['state_registration'] ?? ''); ?>"></label>
<label class="form-field"><span>Inscrição CCM / municipal</span><input class="form-control" type="text" name="municipal_registration" value="<?= htmlspecialchars($item['municipal_registration'] ?? ''); ?>"></label>
<label class="form-field"><span>Síndico</span><select class="form-select" name="syndico_entity_id"><option value="">Selecione</option><?php foreach ($syndics as $entity): ?><option value="<?= (int) $entity['id']; ?>" <?= (int) ($item['syndico_entity_id'] ?? 0) === (int) $entity['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($entity['display_name']); ?></option><?php endforeach; ?></select></label>
<label class="form-field"><span>Administradora</span><select class="form-select" name="administradora_entity_id"><option value="">Selecione</option><?php foreach ($administradorasList as $entity): ?><option value="<?= (int) $entity['id']; ?>" <?= (int) ($item['administradora_entity_id'] ?? 0) === (int) $entity['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($entity['display_name']); ?></option><?php endforeach; ?></select></label>
<label class="form-field"><span>Status</span><select class="form-select" name="is_active" data-inactive-toggle><option value="1" <?= (int) ($item['is_active'] ?? 1) === 1 ? 'selected' : ''; ?>>Ativo</option><option value="0" <?= isset($item['is_active']) && (int) $item['is_active'] === 0 ? 'selected' : ''; ?>>Inativo</option></select></label>
<label class="form-field clients-inactive-fields"><span>Data de término do contrato</span><input class="form-control" type="date" name="contract_end_date" value="<?= htmlspecialchars($item['contract_end_date'] ?? ''); ?>"></label>
<label class="form-field clients-inactive-fields form-grid-full"><span>Motivo da inativação</span><input class="form-control" type="text" name="inactive_reason" value="<?= htmlspecialchars($item['inactive_reason'] ?? ''); ?>"></label>
</div></div>

<div class="card"><div class="card-header"><h3 class="card-title">Endereço</h3></div><div class="card-body form-grid form-grid-4">
<?php foreach (['street' => 'Endereço', 'number' => 'Número', 'complement' => 'Complemento', 'neighborhood' => 'Bairro', 'city' => 'Cidade', 'state' => 'Estado', 'zip' => 'CEP'] as $field => $label): ?>
<label class="form-field"><span><?= $label; ?></span><input class="form-control" type="text" name="address_<?= $field; ?>" value="<?= htmlspecialchars($address[$field] ?? ''); ?>"></label>
<?php endforeach; ?>
<label class="form-field form-grid-full"><span>Observação do endereço</span><input class="form-control" type="text" name="address_notes" value="<?= htmlspecialchars($address['notes'] ?? ''); ?>"></label>
</div></div>

<div class="card clients-blocks-section"><div class="card-header"><h3 class="card-title">Blocos / torres</h3></div><div class="card-body repeater" data-repeater="blocks"><div class="repeater__header"><strong>Estrutura</strong><button class="btn btn-ghost" type="button" data-repeater-add="blocks"><i class="fa-solid fa-plus"></i> Adicionar bloco</button></div><div data-repeater-items="blocks"><?php foreach ($blocks as $index => $block): ?><div class="repeater__row"><input class="form-control" type="text" name="blocks[]" maxlength="50" value="<?= htmlspecialchars($block['name'] ?? $block); ?>" placeholder="Nome do bloco ou torre"><button class="btn btn-danger" type="button" data-repeater-remove>Remover</button></div><?php endforeach; ?></div></div></div>

<div class="card"><div class="card-header"><h3 class="card-title">Dados bancários e características</h3></div><div class="card-body stack-md">
<label class="form-field"><span>Conta bancária</span><textarea class="form-control" name="bank_details" rows="3"><?= htmlspecialchars($item['bank_details'] ?? ''); ?></textarea></label>
<label class="form-field"><span>Características</span><textarea class="form-control" name="characteristics" rows="4"><?= htmlspecialchars($item['characteristics'] ?? ''); ?></textarea></label>
<div class="form-grid form-grid-2"><label class="form-field"><span>Anexos (PDF, JPG, PNG)</span><input class="form-control" type="file" name="attachments[]" multiple></label><label class="form-field"><span>Papel do(s) anexo(s)</span><select class="form-select" name="attachment_role[]"><option value="documento">Documento</option><option value="contrato">Contrato assinado</option><option value="outro">Outro</option></select></label></div>
</div></div>

<?php if ($item): ?>
<div class="dashboard-grid dashboard-grid--clients-detail">
<div class="card"><div class="card-header"><h3 class="card-title">Anexos</h3></div><div class="card-body"><?php if (!$attachments): ?><p class="text-muted">Nenhum anexo enviado.</p><?php else: ?><div class="mini-list"><?php foreach ($attachments as $file): ?><div class="mini-list__item"><div><strong><?= htmlspecialchars($file['original_name']); ?></strong><span><?= htmlspecialchars($file['file_role']); ?></span></div><div class="actions"><a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/anexos/' . $file['id'] . '/download')); ?>">Baixar</a></div></div><?php endforeach; ?></div><?php endif; ?></div></div>
<div class="card"><div class="card-header"><h3 class="card-title">Timeline</h3></div><div class="card-body stack-md"><label class="form-field"><span>Novo registro</span><textarea class="form-control" name="timeline_note" rows="3"></textarea></label><div class="timeline-list"><?php foreach ($timeline as $event): ?><div class="timeline-item"><strong><?= htmlspecialchars(date('d/m/Y H:i', strtotime((string) $event['created_at']))); ?></strong><p><?= nl2br(htmlspecialchars($event['note'])); ?></p><span><?= htmlspecialchars($event['user_email']); ?></span></div><?php endforeach; ?></div></div></div>
</div>
<?php endif; ?>
<div class="section-actions"><button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Salvar condomínio</button><?php if ($item): ?><span class="text-muted">Exclusão disponível pela listagem do módulo.</span><?php endif; ?></div>
</form>
