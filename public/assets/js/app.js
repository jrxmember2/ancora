document.addEventListener('DOMContentLoaded', () => {
  const html = document.documentElement;
  const body = document.body;

  const menuToggle = document.getElementById('menuToggle');
  const mainNav = document.getElementById('mainNav');
  const themeToggle = document.getElementById('themeToggle');
  const themeToggleIcon = document.getElementById('themeToggleIcon');
  const brandLogo = document.getElementById('brandLogo');

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const saveUrl = document.querySelector('meta[name="theme-save-url"]')?.getAttribute('content') || '';
  const brandLogoLight = document.querySelector('meta[name="brand-logo-light"]')?.getAttribute('content') || '';
  const brandLogoDark = document.querySelector('meta[name="brand-logo-dark"]')?.getAttribute('content') || '';

  const logoutLinks = [
    document.getElementById('logoutLink'),
    document.getElementById('logoutLinkMobile')
  ].filter(Boolean);

  function openMenu() {
    if (!mainNav || !menuToggle) return;
    mainNav.classList.add('is-open');
    menuToggle.setAttribute('aria-expanded', 'true');
    body.classList.add('nav-open');
  }

  function closeMenu() {
    if (!mainNav || !menuToggle) return;
    mainNav.classList.remove('is-open');
    menuToggle.setAttribute('aria-expanded', 'false');
    body.classList.remove('nav-open');
  }

  if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', () => {
      const isOpen = mainNav.classList.contains('is-open');
      if (isOpen) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    document.addEventListener('click', (event) => {
      const clickedInsideMenu = mainNav.contains(event.target);
      const clickedToggle = menuToggle.contains(event.target);

      if (!clickedInsideMenu && !clickedToggle) {
        closeMenu();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeMenu();
      }
    });
  }

  logoutLinks.forEach((logoutLink) => {
    logoutLink.addEventListener('click', function (e) {
      e.preventDefault();
      const href = this.getAttribute('href');

      Swal.fire({
        title: 'Tem certeza?',
        text: 'Você será desconectado do sistema.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, sair',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#941415',
        background: html.getAttribute('data-theme') === 'light' ? '#ffffff' : '#1c1718',
        color: html.getAttribute('data-theme') === 'light' ? '#1d1b1c' : '#f4efee'
      }).then((result) => {
        if (result.isConfirmed && href) {
          window.location.href = href;
        }
      });
    });
  });

  document.querySelectorAll('[data-delete-confirm]').forEach((button) => {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      const form = document.querySelector(this.dataset.formTarget);
      const itemLabel = this.dataset.itemLabel || 'este registro';
      if (!form) return;

      Swal.fire({
        title: 'Confirmação obrigatória',
        html: `
          <p>Para excluir <strong>${itemLabel}</strong>, digite <strong>confirmo</strong>.</p>
          <input id="confirmText" class="swal2-input" placeholder="Digite confirmo">
        `,
        confirmButtonText: 'Excluir',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#941415',
        background: html.getAttribute('data-theme') === 'light' ? '#ffffff' : '#1c1718',
        color: html.getAttribute('data-theme') === 'light' ? '#1d1b1c' : '#f4efee',
        didOpen: () => {
          const confirmBtn = Swal.getConfirmButton();
          confirmBtn.disabled = true;

          const input = document.getElementById('confirmText');
          input.addEventListener('input', () => {
            confirmBtn.disabled = input.value.trim().toLowerCase() !== 'confirmo';
          });
        },
        preConfirm: () => {
          const value = document.getElementById('confirmText').value.trim().toLowerCase();
          if (value !== 'confirmo') {
            Swal.showValidationMessage('Digite exatamente confirmo');
            return false;
          }
          return true;
        }
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });

  const tabButtons = document.querySelectorAll('[data-tab-target]');
  const tabPanels = document.querySelectorAll('[data-tab-panel]');

  if (tabButtons.length && tabPanels.length) {
    tabButtons.forEach((button) => {
      button.addEventListener('click', () => {
        const target = button.dataset.tabTarget;

        tabButtons.forEach((btn) => btn.classList.toggle('active', btn === button));
        tabPanels.forEach((panel) => panel.classList.toggle('hidden', panel.dataset.tabPanel !== target));
      });
    });
  }

  document.querySelectorAll('[data-table-filter]').forEach((input) => {
    input.addEventListener('input', () => {
      const table = document.getElementById(input.dataset.tableFilter);
      if (!table) return;

      const term = input.value.trim().toLowerCase();

      table.querySelectorAll('tbody tr').forEach((row) => {
        row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
      });
    });
  });

  function applyTheme(theme) {
    const normalizedTheme = theme === 'light' ? 'light' : 'dark';

    html.setAttribute('data-theme', normalizedTheme);
    body.setAttribute('data-theme', normalizedTheme);
    localStorage.setItem('theme_preference', normalizedTheme);

    if (brandLogo) {
      brandLogo.src = normalizedTheme === 'light' ? brandLogoLight : brandLogoDark;
    }

    if (themeToggleIcon) {
      themeToggleIcon.className = normalizedTheme === 'light'
        ? 'fa-solid fa-moon'
        : 'fa-solid fa-sun';
    }

    if (themeToggle) {
      themeToggle.setAttribute(
        'aria-label',
        normalizedTheme === 'light' ? 'Ativar tema escuro' : 'Ativar tema claro'
      );

      themeToggle.setAttribute(
        'title',
        normalizedTheme === 'light' ? 'Ativar tema escuro' : 'Ativar tema claro'
      );
    }
  }

  const storedTheme = localStorage.getItem('theme_preference');
  if (storedTheme === 'light' || storedTheme === 'dark') {
    applyTheme(storedTheme);
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', async () => {
      const currentTheme = html.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
      const nextTheme = currentTheme === 'light' ? 'dark' : 'light';

      applyTheme(nextTheme);

      if (saveUrl && csrfToken) {
        try {
          const bodyData = new URLSearchParams();
          bodyData.append('_csrf', csrfToken);
          bodyData.append('theme', nextTheme);

          await fetch(saveUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: bodyData.toString()
          });
        } catch (error) {
          console.error('Falha ao salvar preferência de tema:', error);
        }
      }
    });
  }
});