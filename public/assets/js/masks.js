document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-mask="money"]').forEach((input) => {
    input.addEventListener('input', (e) => {
      let value = e.target.value.replace(/\D/g, '');
      value = (Number(value) / 100).toFixed(2) + '';
      value = value.replace('.', ',');
      value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
      e.target.value = value;
    });
  });

  document.querySelectorAll('[data-mask="phone"]').forEach((input) => {
    input.addEventListener('input', (e) => {
      let value = e.target.value.replace(/\D/g, '').slice(0, 11);
      value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
      value = value.replace(/(\d{5})(\d{1,4})$/, '$1-$2');
      e.target.value = value;
    });
  });
});
