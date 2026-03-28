<section class="page-header">
    <h1 class="page-title">Documento Premium</h1>
    <p class="page-subtitle">Monte a proposta visual completa com base no template oficial.</p>
</section>

<form method="post" action="<?= htmlspecialchars(base_url('/propostas/' . $proposta['id'] . '/documento/save')); ?>" class="form-grid">
    <?= csrf_field(); ?>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Identificação</h3></div>
        <div class="card-body form-grid form-grid-2">
            <div class="form-group">
                <label class="form-label">Template</label>
                <select class="form-select" name="template_id" required>
                    <?php foreach ($templates as $template): ?>
                        <option value="<?= (int) $template['id']; ?>" <?= (string) ($document['template_id'] ?? '') === (string) $template['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($template['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Título do documento</label>
                <input class="form-control" type="text" name="document_title" value="<?= htmlspecialchars($document['document_title'] ?? 'Proposta de Honorários'); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Tipo da proposta</label>
                <input class="form-control" type="text" name="proposal_kind" value="<?= htmlspecialchars($document['proposal_kind'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Nome do cliente/condomínio</label>
                <input class="form-control" type="text" name="client_display_name" value="<?= htmlspecialchars($document['client_display_name'] ?? $proposta['client_name']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">A/C</label>
                <input class="form-control" type="text" name="attention_to" value="<?= htmlspecialchars($document['attention_to'] ?? $proposta['requester_name']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Cargo / função</label>
                <input class="form-control" type="text" name="attention_role" value="<?= htmlspecialchars($document['attention_role'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Subtítulo da capa</label>
                <input class="form-control" type="text" name="cover_subtitle" value="<?= htmlspecialchars($document['cover_subtitle'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Imagem da capa (caminho público)</label>
                <input class="form-control" type="text" name="cover_image_path" value="<?= htmlspecialchars($document['cover_image_path'] ?? ''); ?>" placeholder="/assets/uploads/capas/minha-capa.jpg">
            </div>

            <div class="form-group">
                <label class="form-label">Validade (dias)</label>
                <input class="form-control" type="number" name="validity_days" value="<?= (int) ($document['validity_days'] ?? 30); ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Contexto e fechamento</h3></div>
        <div class="card-body form-grid">
            <div class="form-group">
                <label class="form-label">Contexto introdutório</label>
                <textarea class="form-textarea" name="intro_context"><?= htmlspecialchars($document['intro_context'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Introdução do escopo</label>
                <textarea class="form-textarea" name="scope_intro"><?= htmlspecialchars($document['scope_intro'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Mensagem final</label>
                <textarea class="form-textarea" name="closing_message"><?= htmlspecialchars($document['closing_message'] ?? ''); ?></textarea>
            </div>

            <div class="form-grid form-grid-2">
                <label><input type="checkbox" name="show_institutional" <?= !empty($document['show_institutional']) ? 'checked' : ''; ?>> Mostrar página institucional</label>
                <label><input type="checkbox" name="show_services" <?= !empty($document['show_services']) ? 'checked' : ''; ?>> Mostrar página de serviços</label>
                <label><input type="checkbox" name="show_extra_services" <?= !empty($document['show_extra_services']) ? 'checked' : ''; ?>> Mostrar páginas extras</label>
                <label><input type="checkbox" name="show_contacts_page" <?= !empty($document['show_contacts_page']) ? 'checked' : ''; ?>> Mostrar página final de contatos</label>
            </div>
        </div>
    </div>

    <div class="card">
    <div class="card-header">
        <h3 class="card-title">Opções comerciais</h3>
    </div>

    <div class="card-body">
        <div class="section-actions mb-16">
            <button class="btn btn-primary" type="button" id="addOptionBtn">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>

        <div id="optionsWrapper" class="form-grid">
            <?php $optionsData = !empty($options) ? $options : [['title' => '', 'scope_title' => '', 'scope_html' => '', 'fee_label' => '', 'amount_value' => '', 'amount_text' => '', 'payment_terms' => '', 'is_recommended' => 0]]; ?>
            <?php foreach ($optionsData as $index => $option): ?>
                <div class="card premium-option-card" style="padding:16px;" data-option-item>
                    <div class="section-actions mb-16" style="justify-content:space-between;">
                        <strong>Opção comercial <?= $index + 1; ?></strong>
                        <button class="btn btn-danger" type="button" data-remove-option>
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>

                    <div class="form-grid">
                        <div class="form-grid form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Título da opção</label>
                                <input class="form-control" type="text" name="options[<?= $index; ?>][title]" value="<?= htmlspecialchars($option['title'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Título do escopo</label>
                                <input class="form-control" type="text" name="options[<?= $index; ?>][scope_title]" value="<?= htmlspecialchars($option['scope_title'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                                <label class="form-label">Escopo <small>(máx. 360 caracteres)</small></label>
                                <textarea class="form-textarea" name="options[<?= $index; ?>][scope_html]" maxlength="360" data-scope-limit="360" data-autogrow="true" rows="3" style="min-height:88px;height:auto;overflow:hidden;resize:vertical;"><?= htmlspecialchars($option['scope_html'] ?? ''); ?></textarea>
                                <div class="field-hint"><span data-scope-counter>0</span>/360 caracteres</div>
                        </div>

                        <div class="form-grid form-grid-3">
                            <div class="form-group">
                                <label class="form-label">Rótulo do honorário</label>
                                <input class="form-control" type="text" name="options[<?= $index; ?>][fee_label]" value="<?= htmlspecialchars($option['fee_label'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Valor</label>
                                <div class="money-input-wrap">
                                    <span class="money-prefix">R$</span>
                                    <input
                                        class="form-control money-brl"
                                        type="text"
                                        inputmode="decimal"
                                        name="options[<?= $index; ?>][amount_value]"
                                        value="<?= htmlspecialchars($option['amount_value'] !== null && $option['amount_value'] !== '' ? number_format((float) $option['amount_value'], 2, ',', '.') : ''); ?>"
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Valor por extenso</label>
                                <input class="form-control" type="text" name="options[<?= $index; ?>][amount_text]" value="<?= htmlspecialchars($option['amount_text'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Forma de pagamento</label>
                            <textarea class="form-textarea" name="options[<?= $index; ?>][payment_terms]"><?= htmlspecialchars($option['payment_terms'] ?? ''); ?></textarea>
                        </div>

                        <label>
                            <input type="checkbox" name="options[<?= $index; ?>][is_recommended]" <?= !empty($option['is_recommended']) ? 'checked' : ''; ?>>
                            Marcar como opção recomendada
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <template id="optionTemplate">
            <div class="card premium-option-card" style="padding:16px;" data-option-item>
                <div class="section-actions mb-16" style="justify-content:space-between;">
                    <strong>Opção comercial __NUMBER__</strong>
                    <button class="btn btn-danger" type="button" data-remove-option>
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>

                <div class="form-grid">
                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Título da opção</label>
                            <input class="form-control" type="text" name="options[__INDEX__][title]">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Título do escopo</label>
                            <input class="form-control" type="text" name="options[__INDEX__][scope_title]">
                        </div>
                    </div>

                    <div class="form-group">
                            <label class="form-label">Escopo <small>(máx. 360 caracteres)</small></label>
                            <textarea class="form-textarea" name="options[__INDEX__][scope_html]" maxlength="360" data-scope-limit="360" data-autogrow="true" rows="3" style="min-height:88px;height:auto;overflow:hidden;resize:vertical;"></textarea>
                            <div class="field-hint"><span data-scope-counter>0</span>/360 caracteres</div>
                    </div>

                    <div class="form-grid form-grid-3">
                        <div class="form-group">
                            <label class="form-label">Rótulo do honorário</label>
                            <input class="form-control" type="text" name="options[__INDEX__][fee_label]">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Valor</label>
                            <div class="money-input-wrap">
                                <span class="money-prefix">R$</span>
                                <input class="form-control money-brl" type="text" inputmode="decimal" name="options[__INDEX__][amount_value]">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Valor por extenso</label>
                            <input class="form-control" type="text" name="options[__INDEX__][amount_text]">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Forma de pagamento</label>
                        <textarea class="form-textarea" name="options[__INDEX__][payment_terms]"></textarea>
                    </div>

                    <label>
                        <input type="checkbox" name="options[__INDEX__][is_recommended]">
                        Marcar como opção recomendada
                    </label>
                </div>
            </div>
        </template>
    </div>
</div>

    <div class="section-actions">
        <button class="btn btn-primary" type="submit">Salvar Documento Premium</button>
        <a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/propostas/' . $proposta['id'] . '/documento/preview')); ?>">Visualizar</a>
        <a class="btn btn-ghost" href="<?= htmlspecialchars(base_url('/propostas/' . $proposta['id'] . '/documento/pdf')); ?>" target="_blank">PDF</a>
    </div>
</form>