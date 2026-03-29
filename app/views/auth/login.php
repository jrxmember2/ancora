<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <img id="loginBrandLogo"
                src="<?= htmlspecialchars($brandLogoDarkUrl ?? base_url('/imgs/logomarca.svg')); ?>"
                data-logo-light="<?= htmlspecialchars($brandLogoLightUrl ?? base_url('/imgs/logomarca.svg')); ?>"
                data-logo-dark="<?= htmlspecialchars($brandLogoDarkUrl ?? base_url('/imgs/logomarca.svg')); ?>"
                alt="<?= htmlspecialchars($appDisplayName ?? 'Empresa'); ?>">
            <script>
                (function () {
                    try {
                        var theme = localStorage.getItem('theme_preference');
                        var logo = document.getElementById('loginBrandLogo');
                        if (!logo) return;

                        if (theme === 'light') {
                            logo.src = logo.getAttribute('data-logo-light');
                        } else {
                            logo.src = logo.getAttribute('data-logo-dark');
                        }
                    } catch (e) { }
                })();
            </script>

        </div>

        <h1 class="auth-title">Entrar</h1>
        <p class="auth-subtitle">Acesse sua área de trabalho com segurança.</p>

        <?php if (!empty($error)): ?>
            <div class="alert-banner alert-banner--danger mb-16"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="<?= htmlspecialchars(base_url('/login')); ?>" class="form-grid">
            <?= csrf_field(); ?>
            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control" type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Senha</label>
                <input class="form-control" type="password" name="password" id="password" required>
            </div>
            <button class="btn btn-primary" type="submit">
                <i class="fa-solid fa-right-to-bracket"></i>
                Entrar
            </button>
        </form>
    </div>
</div>