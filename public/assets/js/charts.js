window.dashboardCharts = {};

function openDetailsModal(title, records) {
  const html = !records.length
    ? '<p>Nenhum registro encontrado.</p>'
    : records.map(r => `
      <div class="modal-details__item">
        <strong>Proposta #${r.id}</strong>
        <div>Cliente: ${r.client_name}</div>
        <div>Data: ${r.proposal_date_br}</div>
        <div>Valor: ${r.proposal_total_br}</div>
        <div>Status: ${r.status_name}</div>
        ${r.followup_date ? `<div>Follow-up: ${r.followup_date_br || r.followup_date}</div>` : ''}
        ${r.validity_limit_date_br ? `<div>Validade limite: ${r.validity_limit_date_br}</div>` : ''}
        <div style="margin-top:8px;"><a href="${window.APP_BASE_URL}/propostas/${r.id}" class="btn btn-ghost">Abrir proposta</a></div>
      </div>
    `).join('');

  Swal.fire({
    title,
    html: `<div class="modal-details">${html}</div>`,
    width: 760,
    confirmButtonText: 'Fechar'
  });
}

async function fetchDashboardDetails(type, key, year) {
  const url = `${window.APP_BASE_URL}/dashboard/details?type=${encodeURIComponent(type)}&key=${encodeURIComponent(key)}&year=${encodeURIComponent(year)}`;
  const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
  return response.json();
}

document.addEventListener('DOMContentLoaded', () => {
  if (typeof dashboardData === 'undefined') return;

  const textColor = '#cccccc';
  const gridColor = '#333333';
  const monthlyCtx = document.getElementById('chartMonthly');
  const servicesCtx = document.getElementById('chartServices');
  const adminsCtx = document.getElementById('chartAdmins');
  let currentChartType = 'bar';

  function monthlyConfig(type) {
    return {
      type,
      data: {
        labels: dashboardData.monthly_labels,
        datasets: [{ label: 'Propostas', data: dashboardData.monthly_values, tension: 0.35, fill: false }]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#fff' } } },
        scales: {
          x: { ticks: { color: textColor }, grid: { color: gridColor } },
          y: { ticks: { color: textColor }, grid: { color: gridColor }, beginAtZero: true }
        },
        onClick: async (_, elements, chart) => {
          if (!elements.length) return;
          const index = elements[0].index;
          const month = index + 1;
          const label = chart.data.labels[index];
          const response = await fetchDashboardDetails('month', month, dashboardData.year);
          openDetailsModal(`Propostas de ${label}/${dashboardData.year}`, response.records);
        }
      }
    };
  }

  function buildPie(ctx, labels, values, type) {
    if (!ctx) return null;
    return new Chart(ctx, {
      type: 'pie',
      data: { labels, datasets: [{ data: values }] },
      options: {
        plugins: { legend: { labels: { color: '#fff' } } },
        onClick: async (_, elements, chart) => {
          if (!elements.length) return;
          const label = chart.data.labels[elements[0].index];
          const response = await fetchDashboardDetails(type, label, dashboardData.year);
          openDetailsModal(`${type === 'service' ? 'Serviço' : 'Origem'}: ${label}`, response.records);
        }
      }
    });
  }

  function renderMonthlyChart(type) {
    if (!monthlyCtx) return;
    if (window.dashboardCharts.monthly) {
      window.dashboardCharts.monthly.destroy();
    }
    window.dashboardCharts.monthly = new Chart(monthlyCtx, monthlyConfig(type));
  }

  renderMonthlyChart(currentChartType);
  window.dashboardCharts.services = buildPie(servicesCtx, dashboardData.services_labels, dashboardData.services_values, 'service');
  window.dashboardCharts.admins = buildPie(adminsCtx, dashboardData.admins_labels, dashboardData.admins_values, 'administradora');

  document.querySelectorAll('[data-chart-type]').forEach((button) => {
    button.addEventListener('click', () => {
      currentChartType = button.dataset.chartType;
      document.querySelectorAll('[data-chart-type]').forEach((btn) => btn.classList.toggle('is-active', btn === button));
      renderMonthlyChart(currentChartType);
    });
  });
});
