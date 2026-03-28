<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__hero">
            <div>
                <span class="auth-card__tag">Âncora HUB</span>
                <h2 class="auth-card__title">Operação jurídica modular, com visual premium e base pronta para crescer.</h2>
                <p class="auth-card__text">
                    Acesso central ao ecossistema do escritório, com navegação mais suave, temas claro e escuro e estrutura preparada para propostas, clientes e novos módulos.
                </p>
            </div>

            <ul class="auth-card__list">
                <li><i class="fa-solid fa-layer-group"></i><span>Hub central com módulos independentes</span></li>
                <li><i class="fa-solid fa-swatchbook"></i><span>Visual refinado com Tailwind CSS + daisyUI</span></li>
                <li><i class="fa-solid fa-database"></i><span>Base preparada para PostgreSQL e deploy em EasyPanel</span></li>
            </ul>
        </div>

        <div class="auth-card__content">
            <div class="auth-logo">
                <img id="loginBrandLogo"
                    src="<?= htmlspecialchars($brandLogoDarkUrl ?? base_url('/imgs/logomarca.svg')); ?>"
                    data-logo-light="<?= htmlspecialchars($brandLogoLightUrl ?? base_url('/imgs/logomarca.svg')); ?>"
                    data-logo-dark="<?= htmlspecialchars($brandLogoDarkUrl ?? base_url('/imgs/logomarca.svg')); ?>"
                    alt="<?= htmlspecialchars($appDisplayName ?? 'Empresa'); ?>">
            </div>

            <script>
                (function () {
                    try {
                        var theme = localStorage.getItem('theme_preference');
                        var logo = document.getElementById('loginBrandLogo');
                        if (!logo) return;
                        logo.src = theme === 'light' ? logo.getAttribute('data-logo-light') : logo.getAttribute('data-logo-dark');
                    } catch (e) {}
                })();
            </script>

            <h1 class="auth-title">Entrar</h1>
            <p class="auth-subtitle">Acesse sua área de trabalho com segurança.</p>

            <?php if (!empty($error)): ?>
                <div class="alert-banner alert-banner--danger mt-5"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="<?= htmlspecialchars(base_url('/login')); ?>" class="form-grid mt-6">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label" for="email">E-mail</label>
                    <input class="form-control" type="email" name="email" id="email" placeholder="voce@empresa.com.br" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Senha</label>
                    <input class="form-control" type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                <button class="btn btn-primary btn-lg w-full" type="submit">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Entrar no hub
                </button>
            </form>
        </div>
    </div>
</div>
