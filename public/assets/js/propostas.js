document.addEventListener('DOMContentLoaded', () => {
  const statusSelect = document.getElementById('response_status_id');
  const refusalWrap = document.getElementById('refusalReasonWrapper');
  const refusalTextarea = document.getElementById('refusal_reason');
  const closedTotal = document.getElementById('closed_total');

  const referralToggle = document.getElementById('has_referral');
  const referralWrap = document.getElementById('referralWrapper');
  const referralName = document.getElementById('referral_name');

  function syncConditionalFields() {
    if (!statusSelect) return;

    const option = statusSelect.options[statusSelect.selectedIndex];
    const requiresRefusal = option?.dataset.requiresRefusal === '1';
    const requiresClosedValue = option?.dataset.requiresClosedValue === '1';

    if (refusalWrap && refusalTextarea) {
      refusalWrap.classList.toggle('hidden', !requiresRefusal);
      refusalTextarea.required = requiresRefusal;
      if (!requiresRefusal) {
        refusalTextarea.value = '';
      }
    }

    if (closedTotal) {
      closedTotal.disabled = !requiresClosedValue;
      closedTotal.required = requiresClosedValue;
      if (!requiresClosedValue) {
        closedTotal.value = '';
      }
    }
  }

  function syncReferralField() {
    if (!referralToggle || !referralWrap || !referralName) return;

    const enabled = referralToggle.checked;

    referralWrap.classList.toggle('hidden', !enabled);
    referralName.required = enabled;

    if (!enabled) {
      referralName.value = '';
    }
  }

  if (statusSelect) {
    statusSelect.addEventListener('change', syncConditionalFields);
    syncConditionalFields();
  }

  if (referralToggle) {
    referralToggle.addEventListener('change', syncReferralField);
    syncReferralField();
  }
});