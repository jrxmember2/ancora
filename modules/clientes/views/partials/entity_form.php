<?php
$item = $item ?? null;
$attachments = $attachments ?? [];
$timeline = $timeline ?? [];
$entityRoleFixed = $entityRoleFixed ?? null;
$action = $action ?? '#';
$backUrl = $backUrl ?? base_url('/clientes');
$roleOptions = ['outro' => 'Outro', 'sindico' => 'Síndico', 'administradora' => 'Administradora', 'proprietario' => 'Proprietário', 'locatario' => 'Locatário', 'avulso' => 'Cliente avulso'];
$phones = $item['phones'] ?? [['label' => '', 'number' => '']];
$emails = $item['emails'] ?? [['label' => '', 'email' => '']];
$shareholders = $item['shareholders'] ?? [['name' => '', 'cpf' => '', 'role' => '', 'percentage' => '', 'phone' => '', 'email' => '']];
$primaryAddress = $item['primary_address'] ?? [];
$billingAddress = $item['billing_address'] ?? [];
?>
<?php View::partial('clientes/views/partials/subnav', ['currentRoute' => $currentRoute]); ?>
<div class="toolbar toolbar--stack-mobile mb-16">
    <div class="section-actions">
        <a class="btn btn-ghost" href="<?= htmlspecialchars($backUrl); ?>"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
    </div>
    <?php if ($item): ?>
        <div class="toolbar__meta text-muted">Cadastro em <?= htmlspecialchars(date_br($item['created_at'] ?? '')); ?></div>
    <?php endif; ?>
</div>

<form method="post" action="<?= htmlspecialchars($action); ?>" enctype="multipart/form-data" class="stack-lg clients-form">
    <?= csrf_field(); ?>
    <div class="card">
        <div class="card-header card-header--split"><h3 class="card-title">Dados principais</h3></div>
        <div class="card-body form-grid form-grid-3">
            <input type="hidden" name="profile_scope" value="<?= htmlspecialchars($item['profile_scope'] ?? 'avulso'); ?>">
            <label class="form-field"><span>Tipo de pessoa</span>
                <select class="form-select" name="entity_type" data-entity-type-toggle>
                    <option value="pf" <?= ($item['entity_type'] ?? 'pf') === 'pf' ? 'selected' : ''; ?>>Pessoa física</option>
                    <option value="pj" <?= ($item['entity_type'] ?? '') === 'pj' ? 'selected' : ''; ?>>Pessoa jurídica</option>
                </select>
            </label>

            <label class="form-field"><span><?= (($item['entity_type'] ?? 'pf') === 'pj') ? 'Razão social / Nome principal' : 'Nome'; ?></span>
                <input class="form-control" type="text" name="display_name" value="<?= htmlspecialchars($item['display_name'] ?? ''); ?>" required>
            </label>

            <label class="form-field"><span>Nome complementar / fantasia</span>
                <input class="form-control" type="text" name="legal_name" value="<?= htmlspecialchars($item['legal_name'] ?? ''); ?>">
            </label>

            <label class="form-field"><span>CPF / CNPJ</span>
                <input class="form-control" type="text" name="cpf_cnpj" value="<?= htmlspecialchars($item['cpf_cnpj'] ?? ''); ?>">
            </label>

            <label class="form-field"><span>RG / IE</span>
                <input class="form-control" type="text" name="rg_ie" value="<?= htmlspecialchars($item['rg_ie'] ?? ''); ?>">
            </label>

            <?php if ($entityRoleFixed === null): ?>
            <label class="form-field"><span>Papel do cadastro</span>
                <select class="form-select" name="role_tag">
                    <?php foreach ($roleOptions as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key); ?>" <?= ($item['role_tag'] ?? 'outro') === $key ? 'selected' : ''; ?>><?= htmlspecialchars($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php else: ?>
                <input type="hidden" name="role_tag" value="<?= htmlspecialchars($entityRoleFixed); ?>">
            <?php endif; ?>

            <label class="form-field"><span>Status</span>
                <select class="form-select" name="is_active" data-inactive-toggle>
                    <option value="1" <?= (int) ($item['is_active'] ?? 1) === 1 ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?= isset($item['is_active']) && (int) $item['is_active'] === 0 ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </label>

            <label class="form-field clients-inactive-fields"><span>Data de término do contrato</span>
                <input class="form-control" type="date" name="contract_end_date" value="<?= htmlspecialchars($item['contract_end_date'] ?? ''); ?>">
            </label>

            <label class="form-field clients-inactive-fields form-grid-full"><span>Motivo da inativação</span>
                <input class="form-control" type="text" name="inactive_reason" value="<?= htmlspecialchars($item['inactive_reason'] ?? ''); ?>">
            </label>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Contatos</h3></div>
        <div class="card-body stack-md">
            <div class="repeater" data-repeater="phones">
                <div class="repeater__header"><strong>Telefones / celulares</strong><button class="btn btn-ghost" type="button" data-repeater-add="phones"><i class="fa-solid fa-plus"></i> Adicionar</button></div>
                <div data-repeater-items="phones">
                    <?php foreach ($phones as $index => $phone): ?>
                    <div class="repeater__row">
                        <input class="form-control" type="text" name="phones[<?= $index; ?>][label]" value="<?= htmlspecialchars($phone['label'] ?? ''); ?>" placeholder="Rótulo">
                        <input class="form-control" type="text" name="phones[<?= $index; ?>][number]" value="<?= htmlspecialchars($phone['number'] ?? ''); ?>" placeholder="Número">
                        <button class="btn btn-danger" type="button" data-repeater-remove>Remover</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="repeater" data-repeater="emails">
                <div class="repeater__header"><strong>E-mails</strong><button class="btn btn-ghost" type="button" data-repeater-add="emails"><i class="fa-solid fa-plus"></i> Adicionar</button></div>
                <div data-repeater-items="emails">
                    <?php foreach ($emails as $index => $email): ?>
                    <div class="repeater__row">
                        <input class="form-control" type="text" name="emails[<?= $index; ?>][label]" value="<?= htmlspecialchars($email['label'] ?? ''); ?>" placeholder="Rótulo">
                        <input class="form-control" type="email" name="emails[<?= $index; ?>][email]" value="<?= htmlspecialchars($email['email'] ?? ''); ?>" placeholder="E-mail">
                        <button class="btn btn-danger" type="button" data-repeater-remove>Remover</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Endereço</h3></div>
        <div class="card-body form-grid form-grid-4">
            <?php foreach (['street' => 'Endereço', 'number' => 'Número', 'complement' => 'Complemento', 'neighborhood' => 'Bairro', 'city' => 'Cidade', 'state' => 'Estado', 'zip' => 'CEP'] as $field => $label): ?>
                <label class="form-field"><span><?= $label; ?></span><input class="form-control" type="text" name="primary_address_<?= $field; ?>" value="<?= htmlspecialchars($primaryAddress[$field] ?? ''); ?>"></label>
            <?php endforeach; ?>
            <label class="form-field form-grid-full"><span>Observação do endereço</span><input class="form-control" type="text" name="primary_address_notes" value="<?= htmlspecialchars($primaryAddress['notes'] ?? ''); ?>"></label>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Dados adicionais</h3></div>
        <div class="card-body form-grid form-grid-4">
            <label class="form-field"><span>Sexo</span><input class="form-control" type="text" name="gender" value="<?= htmlspecialchars($item['gender'] ?? ''); ?>"></label>
            <label class="form-field"><span>Nacionalidade</span><input class="form-control" type="text" name="nationality" value="<?= htmlspecialchars($item['nationality'] ?? ''); ?>"></label>
            <label class="form-field"><span>Nascimento</span><input class="form-control" type="date" name="birth_date" value="<?= htmlspecialchars($item['birth_date'] ?? ''); ?>"></label>
            <label class="form-field"><span>Profissão</span><input class="form-control" type="text" name="profession" value="<?= htmlspecialchars($item['profession'] ?? ''); ?>"></label>
            <label class="form-field"><span>Estado civil</span><input class="form-control" type="text" name="marital_status" value="<?= htmlspecialchars($item['marital_status'] ?? ''); ?>"></label>
            <label class="form-field"><span>PIS</span><input class="form-control" type="text" name="pis" value="<?= htmlspecialchars($item['pis'] ?? ''); ?>"></label>
            <label class="form-field"><span>Nome do cônjuge</span><input class="form-control" type="text" name="spouse_name" value="<?= htmlspecialchars($item['spouse_name'] ?? ''); ?>"></label>
            <label class="form-field"><span>Nome do pai</span><input class="form-control" type="text" name="father_name" value="<?= htmlspecialchars($item['father_name'] ?? ''); ?>"></label>
            <label class="form-field"><span>Nome da mãe</span><input class="form-control" type="text" name="mother_name" value="<?= htmlspecialchars($item['mother_name'] ?? ''); ?>"></label>
            <label class="form-field"><span>Filhos</span><input class="form-control" type="text" name="children_info" value="<?= htmlspecialchars($item['children_info'] ?? ''); ?>"></label>
            <label class="form-field"><span>CTPS</span><input class="form-control" type="text" name="ctps" value="<?= htmlspecialchars($item['ctps'] ?? ''); ?>"></label>
            <label class="form-field"><span>CNAE</span><input class="form-control" type="text" name="cnae" value="<?= htmlspecialchars($item['cnae'] ?? ''); ?>"></label>
            <label class="form-field"><span>Inscrição estadual</span><input class="form-control" type="text" name="state_registration" value="<?= htmlspecialchars($item['state_registration'] ?? ''); ?>"></label>
            <label class="form-field"><span>Inscrição municipal</span><input class="form-control" type="text" name="municipal_registration" value="<?= htmlspecialchars($item['municipal_registration'] ?? ''); ?>"></label>
            <label class="form-field"><span>Data de abertura</span><input class="form-control" type="date" name="opening_date" value="<?= htmlspecialchars($item['opening_date'] ?? ''); ?>"></label>
            <label class="form-field"><span>Representante legal</span><input class="form-control" type="text" name="legal_representative" value="<?= htmlspecialchars($item['legal_representative'] ?? ''); ?>"></label>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Endereço de cobrança</h3></div>
        <div class="card-body form-grid form-grid-4">
            <?php foreach (['street' => 'Endereço', 'number' => 'Número', 'complement' => 'Complemento', 'neighborhood' => 'Bairro', 'city' => 'Cidade', 'state' => 'Estado', 'zip' => 'CEP'] as $field => $label): ?>
                <label class="form-field"><span><?= $label; ?></span><input class="form-control" type="text" name="billing_address_<?= $field; ?>" value="<?= htmlspecialchars($billingAddress[$field] ?? ''); ?>"></label>
            <?php endforeach; ?>
            <label class="form-field form-grid-full"><span>Observação do endereço de cobrança</span><input class="form-control" type="text" name="billing_address_notes" value="<?= htmlspecialchars($billingAddress['notes'] ?? ''); ?>"></label>
        </div>
    </div>

    <div class="card clients-pj-only">
        <div class="card-header"><h3 class="card-title">Sócios</h3></div>
        <div class="card-body repeater" data-repeater="shareholders">
            <div class="repeater__header"><strong>Quadro societário</strong><button class="btn btn-ghost" type="button" data-repeater-add="shareholders"><i class="fa-solid fa-plus"></i> Adicionar sócio</button></div>
            <div data-repeater-items="shareholders">
                <?php foreach ($shareholders as $index => $row): ?>
                <div class="repeater__row repeater__row--wide">
                    <input class="form-control" type="text" name="shareholders[<?= $index; ?>][name]" value="<?= htmlspecialchars($row['name'] ?? ''); ?>" placeholder="Nome do sócio">
                    <input class="form-control" type="text" name="shareholders[<?= $index; ?>][cpf]" value="<?= htmlspecialchars($row['cpf'] ?? ''); ?>" placeholder="CPF">
                    <input class="form-control" type="text" name="shareholders[<?= $index; ?>][role]" value="<?= htmlspecialchars($row['role'] ?? ''); ?>" placeholder="Cargo/qualificação">
                    <input class="form-control" type="text" name="shareholders[<?= $index; ?>][percentage]" value="<?= htmlspecialchars($row['percentage'] ?? ''); ?>" placeholder="Percentual">
                    <input class="form-control" type="text" name="shareholders[<?= $index; ?>][phone]" value="<?= htmlspecialchars($row['phone'] ?? ''); ?>" placeholder="Telefone">
                    <input class="form-control" type="email" name="shareholders[<?= $index; ?>][email]" value="<?= htmlspecialchars($row['email'] ?? ''); ?>" placeholder="E-mail">
                    <button class="btn btn-danger" type="button" data-repeater-remove>Remover</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Documentos e observações</h3></div>
        <div class="card-body stack-md">
            <label class="form-field"><span>Descrição</span><textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($item['description'] ?? ''); ?></textarea></label>
            <label class="form-field"><span>Observações internas</span><textarea class="form-control" name="notes" rows="3"><?= htmlspecialchars($item['notes'] ?? ''); ?></textarea></label>
            <div class="form-grid form-grid-2">
                <label class="form-field"><span>Anexos (PDF, JPG, PNG)</span><input class="form-control" type="file" name="attachments[]" multiple></label>
                <label class="form-field"><span>Papel do(s) anexo(s)</span>
                    <select class="form-select" name="attachment_role[]">
                        <option value="documento">Documento</option>
                        <option value="contrato">Contrato assinado (PDF)</option>
                        <option value="outro">Outro</option>
                    </select>
                </label>
            </div>
        </div>
    </div>

    <?php if ($item): ?>
    <div class="dashboard-grid dashboard-grid--clients-detail">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Anexos</h3></div>
            <div class="card-body">
                <?php if (!$attachments): ?>
                    <p class="text-muted">Nenhum anexo enviado.</p>
                <?php else: ?>
                    <div class="mini-list">
                        <?php foreach ($attachments as $file): ?>
                            <div class="mini-list__item">
                                <div>
                                    <strong><?= htmlspecialchars($file['original_name']); ?></strong>
                                    <span><?= htmlspecialchars($file['file_role']); ?> · <?= number_format(((int) $file['file_size']) / 1024, 1, ',', '.'); ?> KB</span>
                                </div>
                                <div class="actions">
                                    <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/clientes/anexos/' . $file['id'] . '/download')); ?>">Baixar</a>
                                    
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Timeline</h3></div>
            <div class="card-body stack-md">
                <label class="form-field"><span>Novo registro</span><textarea class="form-control" name="timeline_note" rows="3" placeholder="Escreva uma observação interna para a linha do tempo."></textarea></label>
                <div class="timeline-list">
                    <?php foreach ($timeline as $event): ?>
                        <div class="timeline-item"><strong><?= htmlspecialchars(date('d/m/Y H:i', strtotime((string) $event['created_at']))); ?></strong><p><?= nl2br(htmlspecialchars($event['note'])); ?></p><span><?= htmlspecialchars($event['user_email']); ?></span></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="section-actions">
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Salvar cadastro</button>
        <?php if ($item): ?><span class="text-muted">Exclusão disponível pela listagem do módulo.</span><?php endif; ?>
    </div>
</form>
