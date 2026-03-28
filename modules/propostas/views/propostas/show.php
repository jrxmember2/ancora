<?php $premiumDocument = ProposalDocument::findByPropostaId((int) $proposal['id']); ?>
<section class="page-header">
    <h1 class="page-title">Proposta <?= htmlspecialchars($proposal['proposal_code'] ?? ('#' . (int) $proposal['id'])); ?></h1>
    <p class="page-subtitle">Visualização completa, anexos e histórico da proposta.</p>
</section>

<div class="section-actions mb-24">
    <a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/editar')); ?>">Editar</a>
    <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/imprimir')); ?>" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf"></i> Imprimir / PDF</a>
    <a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/documento')); ?>">Documento Premium</a>
<?php if ($premiumDocument): ?>
    <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/documento/preview')); ?>" target="_blank" rel="noopener">Preview Premium</a>
    <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/documento/pdf')); ?>" target="_blank" rel="noopener">PDF Premium</a>
<?php else: ?>
    <button class="btn btn-ghost" type="button" disabled title="Salve o Documento Premium primeiro">
        Preview Premium
    </button>

    <button class="btn btn-ghost" type="button" disabled title="Salve o Documento Premium primeiro">
        PDF Premium
    </button>
<?php endif; ?>
    <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas')); ?>">Voltar</a>
</div>

<div class="proposal-show-grid">
    <div class="card"><div class="card-body detail-list">
    <div class="detail-item"><strong>Número da Proposta</strong><?= htmlspecialchars($proposal['proposal_code'] ?? ('#' . (int) $proposal['id'])); ?></div>
    <div class="detail-item"><strong>Data</strong><?= htmlspecialchars(date_br($proposal['proposal_date'])); ?></div>
    <div class="detail-item"><strong>Cliente</strong><?= htmlspecialchars($proposal['client_name']); ?></div>
    <div class="detail-item"><strong>Administradora</strong><?= htmlspecialchars($proposal['administradora_name']); ?></div>
    <div class="detail-item"><strong>Serviço</strong><?= htmlspecialchars($proposal['service_name']); ?></div>
    <div class="detail-item"><strong>Valor da Proposta</strong><?= htmlspecialchars(money_br((float) $proposal['proposal_total'])); ?></div>
    <div class="detail-item"><strong>Valor Fechado</strong><?= $proposal['closed_total'] !== null ? htmlspecialchars(money_br((float) $proposal['closed_total'])) : '-'; ?></div>
</div></div>

    <div class="card"><div class="card-body detail-list">
    <div class="detail-item"><strong>Solicitante</strong><?= htmlspecialchars($proposal['requester_name']); ?></div>
    <div class="detail-item"><strong>Telefone</strong><?= htmlspecialchars($proposal['requester_phone'] ?: '-'); ?></div>
    <div class="detail-item"><strong>E-mail</strong><?= htmlspecialchars($proposal['contact_email'] ?: '-'); ?></div>
    <div class="detail-item"><strong>Houve indicação?</strong><?= (int) ($proposal['has_referral'] ?? 0) === 1 ? 'Sim' : 'Não'; ?></div>
    <div class="detail-item"><strong>Nome da indicação</strong><?= htmlspecialchars($proposal['referral_name'] ?: '-'); ?></div>
    <div class="detail-item"><strong>Forma de Envio</strong><span class="icon-method"><i class="<?= htmlspecialchars($proposal['icon_class']); ?>" style="color: <?= htmlspecialchars($proposal['send_method_color']); ?>"></i><?= htmlspecialchars($proposal['send_method_name']); ?></span></div>
    <div class="detail-item"><strong>Status</strong><?= htmlspecialchars($proposal['status_name']); ?></div>
    <div class="detail-item"><strong>Motivo da Recusa</strong><?= nl2br(htmlspecialchars($proposal['refusal_reason'] ?: '-')); ?></div>
</div></div>

    <div class="card"><div class="card-body detail-list">
        <div class="detail-item"><strong>Follow-up</strong><?= htmlspecialchars(date_br($proposal['followup_date'])); ?></div>
        <div class="detail-item"><strong>Validade (dias)</strong><?= (int) $proposal['validity_days']; ?></div>
        <div class="detail-item"><strong>Observações</strong><?= nl2br(htmlspecialchars($proposal['notes'] ?: '-')); ?></div>
    </div></div>

    <div class="card"><div class="card-body detail-list">
        <div class="detail-item"><strong>Criado por</strong><?= htmlspecialchars($proposal['created_by_name']); ?></div>
        <div class="detail-item"><strong>Criado em</strong><?= htmlspecialchars(date('d/m/Y H:i', strtotime($proposal['created_at']))); ?></div>
        <div class="detail-item"><strong>Atualizado por</strong><?= htmlspecialchars($proposal['updated_by_name'] ?: '-'); ?></div>
        <div class="detail-item"><strong>Atualizado em</strong><?= htmlspecialchars(date('d/m/Y H:i', strtotime($proposal['updated_at']))); ?></div>
    </div></div>
</div>

<div class="grid-2 mt-24">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Anexos PDF</h3>
            <span class="badge badge-neutral"><?= count($attachments); ?> arquivo(s)</span>
        </div>
        <div class="card-body">
            <form method="post" action="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/anexos/upload')); ?>" enctype="multipart/form-data" class="form-grid mb-24">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label" for="attachment_pdf">Adicionar PDF</label>
                    <input class="form-control" type="file" name="attachment_pdf" id="attachment_pdf" accept="application/pdf,.pdf" required>
                    <span class="field-hint">Formato PDF · até 8 MB.</span>
                </div>
                <div class="section-actions">
                    <button class="btn btn-secondary" type="submit"><i class="fa-solid fa-upload"></i> Enviar anexo</button>
                </div>
            </form>

            <?php if (empty($attachments)): ?>
                <p class="text-secondary mb-0">Nenhum PDF anexado.</p>
            <?php else: ?>
                <div class="attachment-list">
                    <?php foreach ($attachments as $attachment): ?>
                        <div class="attachment-item">
                            <div class="attachment-item__meta">
                                <strong><?= htmlspecialchars($attachment['original_name']); ?></strong>
                                <span><?= number_format(((int) $attachment['file_size']) / 1024, 1, ',', '.'); ?> KB · <?= htmlspecialchars($attachment['uploaded_by_name']); ?> · <?= htmlspecialchars(date('d/m/Y H:i', strtotime($attachment['created_at']))); ?></span>
                            </div>
                            <div class="actions">
                                <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/anexos/' . $attachment['id'] . '/download')); ?>" target="_blank" rel="noopener">Abrir</a>
                                <form id="delete-attachment-<?= (int) $attachment['id']; ?>" method="post" action="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/anexos/' . $attachment['id'] . '/delete')); ?>">
                                    <?= csrf_field(); ?>
                                    <button type="button" class="btn btn-danger" data-delete-confirm data-item-label="o anexo <?= htmlspecialchars($attachment['original_name']); ?>" data-form-target="#delete-attachment-<?= (int) $attachment['id']; ?>">Remover</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Linha do tempo</h3>
            <span class="badge badge-neutral"><?= count($history); ?> evento(s)</span>
        </div>
        <div class="card-body">
            <?php if (empty($history)): ?>
                <p class="text-secondary mb-0">Ainda não há histórico para esta proposta.</p>
            <?php else: ?>
                <div class="history-timeline">
                    <?php foreach ($history as $event): ?>
                        <div class="history-item">
                            <div class="history-item__head">
                                <strong><?= htmlspecialchars($event['summary']); ?></strong>
                                <span><?= htmlspecialchars(date('d/m/Y H:i', strtotime($event['created_at']))); ?> · <?= htmlspecialchars($event['user_email']); ?></span>
                            </div>
                            <?php if (!empty($event['payload']['changes'])): ?>
                                <div class="history-item__changes">
                                    <?php foreach ($event['payload']['changes'] as $change): ?>
                                        <div class="history-change">
                                            <strong><?= htmlspecialchars($change['label']); ?>:</strong>
                                            <span>de <?= htmlspecialchars($change['from']); ?> para <?= htmlspecialchars($change['to']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif (!empty($event['payload']['original_name'])): ?>
                                <div class="history-item__changes">
                                    <div class="history-change">
                                        <strong>Arquivo:</strong>
                                        <span><?= htmlspecialchars($event['payload']['original_name']); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
