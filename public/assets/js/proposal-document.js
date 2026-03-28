document.addEventListener('DOMContentLoaded', function () {
    const optionsWrapper = document.getElementById('optionsWrapper');
    const addOptionBtn = document.getElementById('addOptionBtn');
    const optionTemplate = document.getElementById('optionTemplate');

    function formatMoneyBr(value) {
        const digits = String(value || '').replace(/\D/g, '');
        if (!digits) return '';

        const number = (parseInt(digits, 10) / 100).toFixed(2);
        return number.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function bindMoneyMask(scope) {
        const fields = scope.querySelectorAll('.money-brl');
        fields.forEach((input) => {
            input.addEventListener('input', function () {
                this.value = formatMoneyBr(this.value);
            });
        });
    }

    function bindScopeCounters(scope) {
    const fields = scope.querySelectorAll('textarea[data-scope-limit]');

    fields.forEach((textarea) => {
        const limit = parseInt(textarea.dataset.scopeLimit || '360', 10);
        const counter = textarea.parentElement?.querySelector('[data-scope-counter]');

        function syncCounter() {
            if (textarea.value.length > limit) {
                textarea.value = textarea.value.slice(0, limit);
            }

            if (counter) {
                counter.textContent = String(textarea.value.length);
            }
        }

        textarea.addEventListener('input', syncCounter);
        syncCounter();
    });
}

function bindAutoGrowTextareas(scope) {
    const fields = scope.querySelectorAll('textarea[data-autogrow="true"]');

    fields.forEach((textarea) => {
        function resize() {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        textarea.addEventListener('input', resize);
        resize();
    });
}

    function refreshOptionTitles() {
        const cards = optionsWrapper ? optionsWrapper.querySelectorAll('[data-option-item]') : [];
        cards.forEach((card, index) => {
            const title = card.querySelector('strong');
            if (title) {
                title.textContent = 'Opção comercial ' + (index + 1);
            }
        });
    }

    function bindRemoveButtons(scope) {
        const buttons = scope.querySelectorAll('[data-remove-option]');
        buttons.forEach((button) => {
            button.addEventListener('click', function () {
                const card = this.closest('[data-option-item]');
                if (!card) return;

                const totalCards = optionsWrapper.querySelectorAll('[data-option-item]').length;
                if (totalCards <= 1) {
                    card.querySelectorAll('input[type="text"], textarea').forEach((field) => field.value = '');
                    card.querySelectorAll('input[type="checkbox"]').forEach((field) => field.checked = false);
                    return;
                }

                card.remove();
                refreshOptionTitles();
            });
        });
    }

    if (optionsWrapper) {
    bindMoneyMask(optionsWrapper);
    bindScopeCounters(optionsWrapper);
    bindAutoGrowTextareas(optionsWrapper);
    bindRemoveButtons(optionsWrapper);
    refreshOptionTitles();
}

    if (addOptionBtn && optionsWrapper && optionTemplate) {
        addOptionBtn.addEventListener('click', function () {
            const index = optionsWrapper.querySelectorAll('[data-option-item]').length;
            let html = optionTemplate.innerHTML;
            html = html.replace(/__INDEX__/g, String(index));
            html = html.replace(/__NUMBER__/g, String(index + 1));

            const temp = document.createElement('div');
            temp.innerHTML = html.trim();

            const newCard = temp.firstElementChild;
            if (!newCard) return;

            optionsWrapper.appendChild(newCard);
            bindMoneyMask(newCard);
            bindScopeCounters(newCard);
            bindAutoGrowTextareas(newCard);
            bindRemoveButtons(newCard);
            refreshOptionTitles();
        });
    }
});