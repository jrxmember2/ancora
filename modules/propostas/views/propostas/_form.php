<?php
$oldValue = static function (string $key, $default = '') use ($old, $proposal) {
    if (!empty($old) && array_key_exists($key, $old)) return $old[$key];
    return $proposal[$key] ?? $default;
};

$hasReferralRaw = $oldValue(
    'has_referral',
    isset($proposal['has_referral'])
        ? (string) $proposal['has_referral']
        : (!empty($proposal['referral_name']) ? '1' : '0')
);

$hasReferralChecked = in_array((string) $hasReferralRaw, ['1', 'true', 'on'], true);
?>
<?php if (!empty($errors)): ?>
    <div class="alert-banner alert-banner--danger mb-16">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($formAction); ?>" class="form-grid" enctype="multipart/form-data">
    <?= csrf_field(); ?>

    <div class="grid-3">
        <div class="form-group">
    <label class="form-label">Número da Proposta</label>
    <input
        class="form-control"
        type="text"
        value="<?= htmlspecialchars((string) ($proposal['proposal_code'] ?? 'Será gerado ao salvar')); ?>"
        readonly
    >
</div>
        <div class="form-group">
            <label class="form-label" for="proposal_date">Data</label>
            <input class="form-control" type="date" name="proposal_date" id="proposal_date" value="<?= htmlspecialchars((string) $oldValue('proposal_date', date('Y-m-d'))); ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="validity_days">Validade da Proposta (dias)</label>
            <input class="form-control" type="number" min="0" name="validity_days" id="validity_days" value="<?= htmlspecialchars((string) $oldValue('validity_days', 0)); ?>">
        </div>
    </div>

    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label class="form-label" for="client_name">Cliente</label>
            <input class="form-control" type="text" name="client_name" id="client_name" value="<?= htmlspecialchars((string) $oldValue('client_name')); ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="administradora_id">Administradora</label>
            <select class="form-select" name="administradora_id" id="administradora_id" required>
                <option value="">Selecione</option>
                <?php foreach ($administradoras as $item): ?>
                    <option value="<?= (int) $item['id']; ?>" <?= (string) $oldValue('administradora_id') === (string) $item['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($item['name']); ?> (<?= htmlspecialchars($item['type']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label class="form-label" for="service_id">Serviço Solicitado</label>
            <select class="form-select" name="service_id" id="service_id" required>
                <option value="">Selecione</option>
                <?php foreach ($servicos as $item): ?>
                    <option value="<?= (int) $item['id']; ?>" <?= (string) $oldValue('service_id') === (string) $item['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label" for="send_method_id">Forma de Envio</label>
            <select class="form-select" name="send_method_id" id="send_method_id" required>
                <option value="">Selecione</option>
                <?php foreach ($formasEnvio as $item): ?>
                    <option value="<?= (int) $item['id']; ?>" <?= (string) $oldValue('send_method_id') === (string) $item['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label class="form-label" for="proposal_total">Valor Total da Proposta</label>
            <input class="form-control" data-mask="money" type="text" name="proposal_total" id="proposal_total" value="<?= htmlspecialchars(str_replace('.', ',', (string) $oldValue('proposal_total'))); ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="closed_total">Valor Total Fechado</label>
            <input class="form-control" data-mask="money" type="text" name="closed_total" id="closed_total" value="<?= htmlspecialchars(str_replace('.', ',', (string) $oldValue('closed_total'))); ?>">
        </div>
    </div>

    <div class="form-grid form-grid-3">
        <div class="form-group">
            <label class="form-label" for="requester_name">Solicitante</label>
            <input class="form-control" type="text" name="requester_name" id="requester_name" value="<?= htmlspecialchars((string) $oldValue('requester_name')); ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="requester_phone">Telefone</label>
            <input class="form-control" data-mask="phone" type="text" name="requester_phone" id="requester_phone" value="<?= htmlspecialchars((string) $oldValue('requester_phone')); ?>">
        </div>
        <div class="form-group">
            <label class="form-label" for="contact_email">E-mail de Contato</label>
            <input class="form-control" type="email" name="contact_email" id="contact_email" value="<?= htmlspecialchars((string) $oldValue('contact_email')); ?>">
        </div>
    </div>

    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label class="form-label">Indicação</label>

            <input type="hidden" name="has_referral" value="0">

            <div class="form-check">
                <input
                    type="checkbox"
                    name="has_referral"
                    id="has_referral"
                    value="1"
                    <?= $hasReferralChecked ? 'checked' : ''; ?>
                >
                <label for="has_referral">Houve indicação?</label>
            </div>
        </div>

        <div class="form-group <?= $hasReferralChecked ? '' : 'hidden'; ?>" id="referralWrapper">
            <label class="form-label" for="referral_name">Nome da indicação</label>
            <input
                class="form-control"
                type="text"
                name="referral_name"
                id="referral_name"
                value="<?= htmlspecialchars((string) $oldValue('referral_name')); ?>"
                placeholder="Digite o nome de quem indicou"
            >
        </div>
    </div>

    <div class="form-grid form-grid-3">
        <div class="form-group">
            <label class="form-label" for="response_status_id">Status do Retorno</label>
            <select class="form-select" name="response_status_id" id="response_status_id" required>
                <option value="">Selecione</option>
                <?php foreach ($statusRetorno as $item): ?>
                    <option
                        value="<?= (int) $item['id']; ?>"
                        data-requires-refusal="<?= (int) $item['requires_refusal_reason']; ?>"
                        data-requires-closed-value="<?= (int) $item['requires_closed_value']; ?>"
                        <?= (string) $oldValue('response_status_id') === (string) $item['id'] ? 'selected' : ''; ?>
                    >
                        <?= htmlspecialchars($item['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label" for="followup_date">Data de Follow-up</label>
            <input class="form-control" type="date" name="followup_date" id="followup_date" value="<?= htmlspecialchars((string) $oldValue('followup_date')); ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Observação</label>
            <span class="field-hint">Campos dinâmicos são exibidos abaixo conforme o status.</span>
        </div>
    </div>

    <div class="form-group hidden" id="refusalReasonWrapper">
        <label class="form-label" for="refusal_reason">Motivo da Recusa</label>
        <textarea class="form-textarea" name="refusal_reason" id="refusal_reason"><?= htmlspecialchars((string) $oldValue('refusal_reason')); ?></textarea>
    </div>

    <div class="form-group">
        <label class="form-label" for="notes">Observação</label>
        <textarea class="form-textarea" name="notes" id="notes"><?= htmlspecialchars((string) $oldValue('notes')); ?></textarea>
    </div>

    <?php if (empty($proposal['id'])): ?>
        <div class="alert-banner alert-banner--info">
            Salve a proposta primeiro para liberar o envio de anexos PDF e a linha do tempo.
        </div>
    <?php endif; ?>

    <div class="section-actions">
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Salvar</button>
        <a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/propostas')); ?>">Cancelar</a>
    </div>
</form>