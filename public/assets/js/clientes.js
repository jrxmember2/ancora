document.addEventListener('DOMContentLoaded', () => {
  const updateInactiveSections = () => {
    document.querySelectorAll('[data-inactive-toggle]').forEach((select) => {
      const form = select.closest('form') || document;
      const wrappers = form.querySelectorAll('.clients-inactive-fields');
      const inactive = select.value === '0';
      wrappers.forEach((el) => {
        el.style.display = inactive ? '' : 'none';
      });
    });
  };

  const updateEntityTypeSections = () => {
    document.querySelectorAll('[data-entity-type-toggle]').forEach((select) => {
      const form = select.closest('form') || document;
      const isPj = select.value === 'pj';
      form.querySelectorAll('.clients-pj-only').forEach((el) => {
        el.style.display = isPj ? '' : 'none';
      });
    });
  };

  const updateBlocksSection = () => {
    document.querySelectorAll('[data-has-blocks-toggle]').forEach((select) => {
      const form = select.closest('form') || document;
      const blockSection = form.querySelector('.clients-blocks-section');
      if (blockSection) {
        blockSection.style.display = select.value === '1' ? '' : 'none';
      }
    });
  };

  const updatePersonModes = () => {
    [['owner', '[data-owner-mode]'], ['tenant', '[data-tenant-mode]']].forEach(([prefix, selector]) => {
      document.querySelectorAll(selector).forEach((select) => {
        const form = select.closest('form') || document;
        const showNew = select.value === 'new';
        const existing = form.querySelector(`.${prefix}-existing`);
        const fresh = form.querySelector(`.${prefix}-new`);
        if (existing) existing.style.display = showNew ? 'none' : '';
        if (fresh) fresh.style.display = showNew ? '' : 'none';
      });
    });
  };

  const refreshRepeaterIndexes = (container, key) => {
    const rows = container.querySelectorAll('[data-repeater-item]');
    rows.forEach((row, index) => {
      row.querySelectorAll('[name]').forEach((input) => {
        input.name = input.name.replace(new RegExp(`${key}\\[\\d+\\]`, 'g'), `${key}[${index}]`);
      });
    });
  };

  document.querySelectorAll('[data-repeater-items]').forEach((container) => {
    Array.from(container.children).forEach((child) => child.setAttribute('data-repeater-item', '1'));
  });

  document.querySelectorAll('[data-repeater-add]').forEach((button) => {
    button.addEventListener('click', () => {
      const key = button.getAttribute('data-repeater-add');
      const container = document.querySelector(`[data-repeater-items="${key}"]`);
      if (!container) return;
      const first = container.querySelector('[data-repeater-item]') || container.firstElementChild;
      if (!first) return;
      const clone = first.cloneNode(true);
      clone.setAttribute('data-repeater-item', '1');
      clone.querySelectorAll('input, textarea, select').forEach((field) => {
        if (field.tagName === 'SELECT') {
          field.selectedIndex = 0;
        } else {
          field.value = '';
        }
      });
      container.appendChild(clone);
      refreshRepeaterIndexes(container, key);
    });
  });

  document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-repeater-remove]');
    if (!button) return;
    const row = button.closest('[data-repeater-item]') || button.closest('.repeater__row');
    const container = button.closest('[data-repeater-items]');
    if (!row || !container) return;
    if (container.children.length === 1) {
      row.querySelectorAll('input, textarea').forEach((field) => field.value = '');
      return;
    }
    row.remove();
    const key = container.getAttribute('data-repeater-items');
    if (key) refreshRepeaterIndexes(container, key);
  });

  updateInactiveSections();
  updateEntityTypeSections();
  updateBlocksSection();
  updatePersonModes();

  document.addEventListener('change', (event) => {
    if (event.target.matches('[data-inactive-toggle]')) updateInactiveSections();
    if (event.target.matches('[data-entity-type-toggle]')) updateEntityTypeSections();
    if (event.target.matches('[data-has-blocks-toggle]')) updateBlocksSection();
    if (event.target.matches('[data-owner-mode], [data-tenant-mode]')) updatePersonModes();
  });
});
