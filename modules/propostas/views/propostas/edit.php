<section class="page-header">
    <h1 class="page-title">Editar Proposta #<?= (int) $proposal['id']; ?></h1>
    <p class="page-subtitle">Atualize os dados da proposta e gerencie anexos PDF.</p>
</section>

<?php $formAction = base_url('/propostas/' . $proposal['id'] . '/update'); require __DIR__ . '/_form.php'; ?>

<div class="grid-2 mt-24">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Anexos PDF</h3>
            <span class="badge badge-neutral"><?= count($attachments); ?> arquivo(s)</span>
        </div>
        <div class="card-body">
            <form method="post" action="<?= htmlspecialchars(base_url('/propostas/' . $proposal['id'] . '/anexos/upload')); ?>" enctype="multipart/form-data" class="form-grid">
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
                <div class="attachment-list mt-24">
                    <?php foreach ($attachments as $attachment): ?>
                        <div class="attachment-item">
                            <div class="attachment-item__meta">
                                <strong><?= htmlspecialchars($attachment['original_name']); ?></strong>
                                <span><?= number_format(((int) $attachment['file_size']) / 1024, 1, ',', '.'); ?> KB · <?= htmlspecialchars(date('d/m/Y H:i', strtotime($attachment['created_at']))); ?></span>
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
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
