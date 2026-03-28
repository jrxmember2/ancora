<section class="page-header page-header--actions">
    <div>
        <h1 class="page-title">Configurações</h1>
        <p class="page-subtitle">Cadastros auxiliares, usuários e manutenção do módulo.</p>
    </div>
    <div class="page-header__actions">
        <a class="btn btn-secondary" href="<?= htmlspecialchars(base_url('/logs')); ?>">
            <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
            Ver logs
        </a>
    </div>
</section>

<div class="tabs" data-tabs>
    <button class="tab-link active" type="button" data-tab-target="administradoras">Administradoras</button>
    <button class="tab-link" type="button" data-tab-target="servicos">Serviços</button>
    <button class="tab-link" type="button" data-tab-target="status">Status</button>
    <button class="tab-link" type="button" data-tab-target="envio">Formas de Envio</button>
    <button class="tab-link" type="button" data-tab-target="branding">Branding</button>
    <button class="tab-link" type="button" data-tab-target="usuarios">Usuários</button>
</div>

<div class="config-grid">
    <div class="card config-card" data-tab-panel="administradoras">
        <div class="card-header">
            <h3 class="card-title">Administradoras / Síndicos</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?= htmlspecialchars(base_url('/config/administradoras/store')); ?>" class="form-grid form-grid-3 mb-24">
                <?= csrf_field(); ?>
                <input class="form-control" name="name" placeholder="Nome" required>
                <select class="form-select" name="type">
                    <option value="administradora">Administradora</option>
                    <option value="sindico">Síndico</option>
                </select>
                <input class="form-control" name="contact_name" placeholder="Contato">
                <input class="form-control" data-mask="phone" name="phone" placeholder="Telefone">
                <input class="form-control" name="email" type="email" placeholder="E-mail">
                <div class="inline-form">
                    <input class="form-control" name="sort_order" type="number" placeholder="Ordem" value="0">
                    <label><input type="checkbox" name="is_active" checked> Ativo</label>
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </form>

            <div class="table-search">
                <input class="form-control" type="search" placeholder="Filtrar nesta tabela..." data-table-filter="administradoras-table">
            </div>

            <div class="table-wrap">
                <table class="table" id="administradoras-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Contato</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($administradoras as $item): ?>
                            <tr>
                                <form method="post" action="<?= htmlspecialchars(base_url('/config/administradoras/' . $item['id'] . '/update')); ?>">
                                    <?= csrf_field(); ?>
                                    <td><input class="form-control" name="name" value="<?= htmlspecialchars($item['name']); ?>"></td>
                                    <td>
                                        <select class="form-select" name="type">
                                            <option value="administradora" <?= $item['type'] === 'administradora' ? 'selected' : ''; ?>>Administradora</option>
                                            <option value="sindico" <?= $item['type'] === 'sindico' ? 'selected' : ''; ?>>Síndico</option>
                                        </select>
                                    </td>
                                    <td><input class="form-control" name="contact_name" value="<?= htmlspecialchars((string) $item['contact_name']); ?>"></td>
                                    <td><input class="form-control" data-mask="phone" name="phone" value="<?= htmlspecialchars((string) $item['phone']); ?>"></td>
                                    <td><input class="form-control" name="email" value="<?= htmlspecialchars((string) $item['email']); ?>"></td>
                                    <td>
                                        <label><input type="checkbox" name="is_active" <?= (int) $item['is_active'] === 1 ? 'checked' : ''; ?>> Ativo</label>
                                        <input type="hidden" name="sort_order" value="<?= (int) $item['sort_order']; ?>">
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-secondary" type="submit">Salvar</button>
                                </form>
                                <form id="adm-del-<?= (int) $item['id']; ?>" method="post" action="<?= htmlspecialchars(base_url('/config/administradoras/' . $item['id'] . '/delete')); ?>">
                                    <?= csrf_field(); ?>
                                    <button type="button" class="btn btn-danger" data-delete-confirm data-item-label="<?= htmlspecialchars($item['name']); ?>" data-form-target="#adm-del-<?= (int) $item['id']; ?>">Excluir</button>
                                </form>
                                        </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card config-card hidden" data-tab-panel="servicos">
        <div class="card-header">
            <h3 class="card-title">Serviços</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?= htmlspecialchars(base_url('/config/servicos/store')); ?>" class="form-grid form-grid-3 mb-24">
                <?= csrf_field(); ?>
                <input class="form-control" name="name" placeholder="Nome" required>
                <input class="form-control" name="description" placeholder="Descrição">
                <div class="inline-form">
                    <input class="form-control" name="sort_order" type="number" value="0">
                    <label><input type="checkbox" name="is_active" checked> Ativo</label>
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </form>

            <div class="table-search">
                <input class="form-control" type="search" placeholder="Filtrar nesta tabela..." data-table-filter="servicos-table">
            </div>

            <div class="table-wrap">
                <table class="table" id="servicos-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicos as $item): ?>
                            <tr>
                                <form method="post" action="<?= htmlspecialchars(base_url('/config/servicos/' . $item['id'] . '/update')); ?>">
                                    <?= csrf_field(); ?>
                                    <td><input class="form-control" name="name" value="<?= htmlspecialchars($item['name']); ?>"></td>
                                    <td><input class="form-control" name="description" value="<?= htmlspecialchars((string) $item['description']); ?>"></td>
                                    <td>
                                        <label><input type="checkbox" name="is_active" <?= (int) $item['is_active'] === 1 ? 'checked' : ''; ?>> Ativo</label>
                                        <input type="hidden" name="sort_order" value="<?= (int) $item['sort_order']; ?>">
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-secondary" type="submit">Salvar</button>
                                </form>
                                <form id="srv-del-<?= (int) $item['id']; ?>" method="post" action="<?= htmlspecialchars(base_url('/config/servicos/' . $item['id'] . '/delete')); ?>">
                                    <?= csrf_field(); ?>
                                    <button type="button" class="btn btn-danger" data-delete-confirm data-item-label="<?= htmlspecialchars($item['name']); ?>" data-form-target="#srv-del-<?= (int) $item['id']; ?>">Excluir</button>
                                </form>
                                        </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card config-card hidden" data-tab-panel="status">
        <div class="card-header">
            <h3 class="card-title">Status de Retorno</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?= htmlspecialchars(base_url('/config/status/store')); ?>" class="form-grid form-grid-3 mb-24">
                <?= csrf_field(); ?>
                <input class="form-control" name="system_key" placeholder="system_key" required>
                <input class="form-control" name="name" placeholder="Nome" required>
                <input class="form-control" name="color_hex" placeholder="#999999" value="#999999" required>
                <label><input type="checkbox" name="requires_closed_value"> Exige valor fechado</label>
                <label><input type="checkbox" name="requires_refusal_reason"> Exige motivo da recusa</label>
                <label><input type="checkbox" name="stop_followup_alert"> Para alerta de follow-up</label>
                <div class="inline-form">
                    <input class="form-control" name="sort_order" type="number" value="0">
                    <label><input type="checkbox" name="is_active" checked> Ativo</label>
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </form>

            <div class="table-search">
                <input class="form-control" type="search" placeholder="Filtrar nesta tabela..." data-table-filter="status-table">
            </div>

            <div class="table-wrap">
                <table class="table" id="status-table">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Nome</th>
                            <th>Cor</th>
                            <th>Regras</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statusRetorno as $item): ?>
                            <tr>
                                <form method="post" action="<?= htmlspecialchars(base_url('/config/status/' . $item['id'] . '/update')); ?>">
                                    <?= csrf_field(); ?>
                                    <td><input class="form-control" name="system_key" value="<?= htmlspecialchars($item['system_key']); ?>"></td>
                                    <td><input class="form-control" name="name" value="<?= htmlspecialchars($item['name']); ?>"></td>
                                    <td><input class="form-control" name="color_hex" value="<?= htmlspecialchars($item['color_hex']); ?>"></td>
                                    <td>
                                        <label><input type="checkbox" name="requires_closed_value" <?= (int) $item['requires_closed_value'] === 1 ? 'checked' : ''; ?>> Valor fechado</label><br>
                                        <label><input type="checkbox" name="requires_refusal_reason" <?= (int) $item['requires_refusal_reason'] === 1 ? 'checked' : ''; ?>> Motivo recusa</label><br>
                                        <label><input type="checkbox" name="stop_followup_alert" <?= (int) $item['stop_followup_alert'] === 1 ? 'checked' : ''; ?>> Para alerta</label><br>
                                        <label><input type="checkbox" name="is_active" <?= (int) $item['is_active'] === 1 ? 'checked' : ''; ?>> Ativo</label>
                                        <input type="hidden" name="sort_order" value="<?= (int) $item['sort_order']; ?>">
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-secondary" type="submit">Salvar</button>
                                </form>
                                <form id="sta-del-<?= (int) $item['id']; ?>" method="post" action="<?= htmlspecialchars(base_url('/config/status/' . $item['id'] . '/delete')); ?>">
                                    <?= csrf_field(); ?>
                                    <button type="button" class="btn btn-danger" data-delete-confirm data-item-label="<?= htmlspecialchars($item['name']); ?>" data-form-target="#sta-del-<?= (int) $item['id']; ?>">Excluir</button>
                                </form>
                                        </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card config-card hidden" data-tab-panel="envio">
        <div class="card-header">
            <h3 class="card-title">Formas de Envio</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?= htmlspecialchars(base_url('/config/formas-envio/store')); ?>" class="form-grid form-grid-3 mb-24">
                <?= csrf_field(); ?>
                <input class="form-control" name="name" placeholder="Nome" required>
                <input class="form-control" name="icon_class" placeholder="fa-solid fa-envelope" required>
                <input class="form-control" name="color_hex" placeholder="#3B82F6" value="#3B82F6" required>
                <div class="inline-form">
                    <input class="form-control" name="sort_order" type="number" value="0">
                    <label><input type="checkbox" name="is_active" checked> Ativo</label>
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </form>

            <div class="table-search">
                <input class="form-control" type="search" placeholder="Filtrar nesta tabela..." data-table-filter="envio-table">
            </div>

            <div class="table-wrap">
                <table class="table" id="envio-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Ícone</th>
                            <th>Cor</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($formasEnvio as $item): ?>
                            <tr>
                                <form method="post" action="<?= htmlspecialchars(base_url('/config/formas-envio/' . $item['id'] . '/update')); ?>">
                                    <?= csrf_field(); ?>
                                    <td><input class="form-control" name="name" value="<?= htmlspecialchars($item['name']); ?>"></td>
                                    <td><input class="form-control" name="icon_class" value="<?= htmlspecialchars($item['icon_class']); ?>"></td>
                                    <td><input class="form-control" name="color_hex" value="<?= htmlspecialchars($item['color_hex']); ?>"></td>
                                    <td>
                                        <label><input type="checkbox" name="is_active" <?= (int) $item['is_active'] === 1 ? 'checked' : ''; ?>> Ativo</label>
                                        <input type="hidden" name="sort_order" value="<?= (int) $item['sort_order']; ?>">
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-secondary" type="submit">Salvar</button>
                                </form>
                                <form id="fe-del-<?= (int) $item['id']; ?>" method="post" action="<?= htmlspecialchars(base_url('/config/formas-envio/' . $item['id'] . '/delete')); ?>">
                                    <?= csrf_field(); ?>
                                    <button type="button" class="btn btn-danger" data-delete-confirm data-item-label="<?= htmlspecialchars($item['name']); ?>" data-form-target="#fe-del-<?= (int) $item['id']; ?>">Excluir</button>
                                </form>
                                        </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card config-card hidden" data-tab-panel="branding">
    <div class="card-header">
        <h3 class="card-title">Branding do Sistema</h3>
    </div>
    <div class="card-body">
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Empresa e Logo</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= htmlspecialchars(base_url('/config/branding/save')); ?>" enctype="multipart/form-data" class="form-grid">
                        <?= csrf_field(); ?>
                        <input
                            type="hidden"
                            name="premium_logo_variant"
                            id="premium_logo_variant"
                            value="<?= htmlspecialchars($branding['premium_logo_variant'] ?? 'light'); ?>"
                        >

                        <div class="form-grid form-grid-2">
                            <div class="form-group">
                                <label class="form-label" for="company_name">Nome da empresa</label>
                                <input class="form-control" type="text" name="company_name" id="company_name" value="<?= htmlspecialchars($branding['company_name']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="company_email">E-mail</label>
                                <input class="form-control" type="email" name="company_email" id="company_email" value="<?= htmlspecialchars($branding['company_email']); ?>">
                            </div>
                        </div>

                        <div class="form-grid form-grid-2">
                            <div class="form-group">
                                <label class="form-label" for="company_phone">Telefone</label>
                                <input class="form-control" data-mask="phone" type="text" name="company_phone" id="company_phone" value="<?= htmlspecialchars($branding['company_phone']); ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="company_address">Endereço</label>
                                <input class="form-control" type="text" name="company_address" id="company_address" value="<?= htmlspecialchars($branding['company_address']); ?>">
                            </div>
                        </div>

                        <div class="form-grid form-grid-2">
    <div class="form-group">
            <label class="form-label">Logo atual do tema claro</label>

            <div class="card" style="padding:20px;display:flex;align-items:center;justify-content:center;min-height:120px;">
                <img
                    src="<?= htmlspecialchars($branding['logo_light_url']); ?>"
                    alt="Logo do tema claro"
                    style="max-height:90px;width:auto;max-width:100%;object-fit:contain;"
                >
    </div>

            <label class="form-check" style="margin-top:10px;justify-content:flex-start;gap:10px;">
                <input
                    type="checkbox"
                    class="premium-logo-choice"
                    data-premium-logo-choice="light"
                    <?= ($branding['premium_logo_variant'] ?? 'light') === 'light' ? 'checked' : ''; ?>
                >
                <span>Usar esta logo no preview/PDF premium</span>
            </label>
    </div>

    <div class="form-group">
        <label class="form-label" for="branding_logo_light">Nova logo do tema claro</label>
        <input class="form-control" type="file" name="branding_logo_light" id="branding_logo_light" accept=".svg,.png,.jpg,.jpeg,.webp,image/svg+xml,image/png,image/jpeg,image/webp">
        <span class="field-hint">Use a logo ideal para fundo claro.</span>
    </div>
</div>

<div class="form-grid form-grid-2">
    <div class="form-group">
    <label class="form-label">Logo atual do tema escuro</label>

    <div class="card" style="padding:20px;display:flex;align-items:center;justify-content:center;min-height:120px;">
        <img
            src="<?= htmlspecialchars($branding['logo_dark_url']); ?>"
            alt="Logo do tema escuro"
            style="max-height:90px;width:auto;max-width:100%;object-fit:contain;"
        >
    </div>

    <label class="form-check" style="margin-top:10px;justify-content:flex-start;gap:10px;">
        <input
            type="checkbox"
            class="premium-logo-choice"
            data-premium-logo-choice="dark"
            <?= ($branding['premium_logo_variant'] ?? 'light') === 'dark' ? 'checked' : ''; ?>
        >
        <span>Usar esta logo no preview/PDF premium</span>
    </label>
</div>

    <div class="form-group">
        <label class="form-label" for="branding_logo_dark">Nova logo do tema escuro</label>
        <input class="form-control" type="file" name="branding_logo_dark" id="branding_logo_dark" accept=".svg,.png,.jpg,.jpeg,.webp,image/svg+xml,image/png,image/jpeg,image/webp">
        <span class="field-hint">Use a logo ideal para fundo escuro.</span>
    </div>
</div>

                        <div class="form-grid form-grid-3">
                            <div class="form-group">
                                <label class="form-label" for="logo_height_desktop">Altura da logo no desktop</label>
                                <input class="form-control" type="number" min="20" max="140" name="logo_height_desktop" id="logo_height_desktop" value="<?= (int) $branding['logo_height_desktop']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="logo_height_mobile">Altura da logo no mobile</label>
                                <input class="form-control" type="number" min="20" max="120" name="logo_height_mobile" id="logo_height_mobile" value="<?= (int) $branding['logo_height_mobile']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="logo_height_login">Altura da logo no login</label>
                                <input class="form-control" type="number" min="30" max="220" name="logo_height_login" id="logo_height_login" value="<?= (int) $branding['logo_height_login']; ?>" required>
                            </div>
                        </div>

                        <div class="section-actions">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Salvar branding
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Favicon</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= htmlspecialchars(base_url('/config/favicon/save')); ?>" enctype="multipart/form-data" class="form-grid">
                        <?= csrf_field(); ?>

                        <div class="form-group">
                            <label class="form-label">Favicon atual</label>
                            <div class="card" style="padding:20px;display:flex;align-items:center;justify-content:center;min-height:100px;">
                                <img
                                    src="<?= htmlspecialchars($branding['favicon_url']); ?>"
                                    alt="Favicon atual"
                                    style="max-height:48px;width:auto;max-width:100%;object-fit:contain;"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="branding_favicon">Novo favicon</label>
                            <input class="form-control" type="file" name="branding_favicon" id="branding_favicon" accept=".ico,.png,.svg,image/x-icon,image/png,image/svg+xml" required>
                            <span class="field-hint">Formatos aceitos: ICO, PNG e SVG. Máximo de 1 MB.</span>
                        </div>

                        <div class="section-actions">
                            <button class="btn btn-secondary" type="submit">
                                <i class="fa-solid fa-image"></i>
                                Salvar favicon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-24">
            <div class="card-header">
                <h3 class="card-title">Módulos habilitados</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?= htmlspecialchars(base_url('/config/modules/save')); ?>" class="form-grid">
                    <?= csrf_field(); ?>

                    <div class="form-grid form-grid-2">
                        <?php foreach ($modules as $module): ?>
                            <?php $locked = in_array($module['slug'], ['dashboard', 'propostas', 'config'], true); ?>
                            <label class="form-check" style="justify-content:space-between;">
                                <span>
                                    <strong><?= htmlspecialchars($module['name']); ?></strong>
                                    <small style="display:block;color:var(--text-muted);"><?= htmlspecialchars($module['slug']); ?></small>
                                </span>
                                <span>
                                    <input
                                        type="checkbox"
                                        name="enabled_modules[]"
                                        value="<?= (int) $module['id']; ?>"
                                        <?= (int) $module['is_enabled'] === 1 ? 'checked' : ''; ?>
                                        <?= $locked ? 'disabled' : ''; ?>
                                    >
                                    <?php if ($locked): ?>
                                        <input type="hidden" name="enabled_modules[]" value="<?= (int) $module['id']; ?>">
                                    <?php endif; ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="section-actions">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-puzzle-piece"></i>
                            Salvar módulos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="card config-card hidden" data-tab-panel="usuarios">
        <div class="card-header">
            <h3 class="card-title">Usuários</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?= htmlspecialchars(base_url('/config/usuarios/store')); ?>" class="form-grid form-grid-3 mb-24">
                <?= csrf_field(); ?>
                <input class="form-control" name="name" placeholder="Nome" required>
                <input class="form-control" name="email" type="email" placeholder="E-mail" required>
                <input class="form-control" name="password" type="password" placeholder="Senha" required>
                <select class="form-select" name="role">
                    <option value="comum">Comum</option>
                    <option value="superadmin">Superadmin</option>
                </select>
                <label><input type="checkbox" name="is_active" checked> Ativo</label>
                <div style="grid-column:1 / -1;">
                    <label class="mb-8" style="display:block;font-weight:700;">Módulos permitidos</label>
                    <div class="checkbox-grid">
                        <?php foreach ($moduleCatalog as $moduleOption): ?>
                            <?php if (in_array($moduleOption['slug'], ['config', 'logs'], true)): continue; endif; ?>
                            <label><input type="checkbox" name="module_permissions[]" value="<?= htmlspecialchars($moduleOption['slug']); ?>" <?= in_array($moduleOption['slug'], ['dashboard', 'propostas', 'busca'], true) ? 'checked' : ''; ?>> <?= htmlspecialchars($moduleOption['name']); ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Adicionar usuário</button>
            </form>

            <div class="table-search">
                <input class="form-control" type="search" placeholder="Filtrar nesta tabela..." data-table-filter="usuarios-table">
            </div>

            <div class="table-wrap">
                <table class="table" id="usuarios-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Status</th>
                            <th>Senha</th>
                            <th>Módulos</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $item): ?>
                            <tr>
                                <form method="post" action="<?= htmlspecialchars(base_url('/config/usuarios/' . $item['id'] . '/update')); ?>">
                                    <?= csrf_field(); ?>
                                    <td><input class="form-control" name="name" value="<?= htmlspecialchars($item['name']); ?>"></td>
                                    <td><input class="form-control" name="email" value="<?= htmlspecialchars($item['email']); ?>"></td>
                                    <td>
                                        <select class="form-select" name="role" <?= (int) $item['is_protected'] === 1 ? 'disabled' : ''; ?>>
                                            <option value="comum" <?= $item['role'] === 'comum' ? 'selected' : ''; ?>>Comum</option>
                                            <option value="superadmin" <?= $item['role'] === 'superadmin' ? 'selected' : ''; ?>>Superadmin</option>
                                        </select>
                                        <?php if ((int) $item['is_protected'] === 1): ?>
                                            <input type="hidden" name="role" value="superadmin">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <label><input type="checkbox" name="is_active" <?= (int) $item['is_active'] === 1 ? 'checked' : ''; ?> <?= (int) $item['is_protected'] === 1 ? 'disabled' : ''; ?>> Ativo</label>
                                        <?php if ((int) $item['is_protected'] === 1): ?>
                                            <input type="hidden" name="is_active" value="1">
                                        <?php endif; ?>
                                    </td>
                                    <td><input class="form-control" name="password" type="password" placeholder="Nova senha"></td>
                                    <td>
                                        <?php if ($item['role'] === 'superadmin'): ?>
                                            <span class="badge badge-info">Todos os módulos</span>
                                        <?php else: ?>
                                            <div class="checkbox-grid checkbox-grid--compact">
                                                <?php foreach ($moduleCatalog as $moduleOption): ?>
                                                    <?php if (in_array($moduleOption['slug'], ['config', 'logs'], true)): continue; endif; ?>
                                                    <label>
                                                        <input type="checkbox" name="module_permissions[]" value="<?= htmlspecialchars($moduleOption['slug']); ?>" <?= in_array($moduleOption['slug'], $item['module_permissions'] ?? [], true) ? 'checked' : ''; ?>>
                                                        <?= htmlspecialchars($moduleOption['short_name'] ?? $moduleOption['name']); ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-secondary" type="submit">Salvar</button>
                                </form>
                                <?php if ((int) $item['is_protected'] === 1): ?>
                                    <button class="btn btn-danger" type="button" disabled>Protegido</button>
                                <?php else: ?>
                                    <form id="usr-del-<?= (int) $item['id']; ?>" method="post" action="<?= htmlspecialchars(base_url('/config/usuarios/' . $item['id'] . '/delete')); ?>">
                                        <?= csrf_field(); ?>
                                        <button type="button" class="btn btn-danger" data-delete-confirm data-item-label="<?= htmlspecialchars($item['email']); ?>" data-form-target="#usr-del-<?= (int) $item['id']; ?>">Excluir</button>
                                    </form>
                                <?php endif; ?>
                                        </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hidden = document.getElementById('premium_logo_variant');
    const boxes = Array.from(document.querySelectorAll('.premium-logo-choice'));

    if (!hidden || !boxes.length) {
        return;
    }

    function activate(value) {
        boxes.forEach((box) => {
            box.checked = box.dataset.premiumLogoChoice === value;
        });
        hidden.value = value;
    }

    const initial =
        boxes.find((box) => box.checked)?.dataset.premiumLogoChoice ||
        hidden.value ||
        'light';

    activate(initial === 'dark' ? 'dark' : 'light');

    boxes.forEach((box) => {
        box.addEventListener('change', function () {
            activate(this.dataset.premiumLogoChoice === 'dark' ? 'dark' : 'light');
        });
    });
});
</script>
<style>.checkbox-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:8px 14px}.checkbox-grid--compact{grid-template-columns:repeat(auto-fit,minmax(120px,1fr));max-width:420px}.checkbox-grid label{display:flex;align-items:center;gap:8px;font-size:13px}.badge-info{display:inline-flex;padding:6px 10px;border-radius:999px;background:rgba(59,130,246,.12);color:#3b82f6;font-weight:700;font-size:12px}</style>
<script>document.addEventListener("DOMContentLoaded",function(){document.querySelectorAll("select[name=role]").forEach(function(select){var form=select.closest("form");if(!form)return;var wrap=form.querySelector(".checkbox-grid")?.closest("div");function sync(){if(!wrap)return;wrap.style.opacity=select.value==="superadmin"?".55":"1";wrap.querySelectorAll("input[type=checkbox]").forEach(function(cb){cb.disabled=select.value==="superadmin";});}select.addEventListener("change",sync);sync();});});</script>
