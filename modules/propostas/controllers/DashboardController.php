<?php
declare(strict_types=1);

final class DashboardController extends BaseController
{
    public function index(): void
    {
        require_auth();
        require_enabled_module('propostas');
        
        $availableYears = Proposta::yearsAvailable();
        $defaultYear = !empty($availableYears) ? (int) $availableYears[0] : (int) date('Y');

        $year = (int) ($this->get('year', $defaultYear));

        $data = DashboardService::summary($year);

        $this->view('propostas/views/dashboard/index', [
            'title' => 'Dashboard',
            'currentRoute' => 'dashboard',
            'dashboard' => $data,
        ]);
    }

    public function details(): void
    {
        require_auth();
        require_enabled_module('propostas');

        $availableYears = Proposta::yearsAvailable();
        $defaultYear = !empty($availableYears) ? (int) $availableYears[0] : (int) date('Y');

        $type = (string) $this->get('type', '');
        $key = (string) $this->get('key', '');
        $year = (int) $this->get('year', $defaultYear);

        $records = Proposta::forDashboardDetail($type, $key, $year);

        $records = array_map(static function (array $row): array {
            $row['proposal_date_br'] = date_br($row['proposal_date'] ?? null);
            $row['followup_date_br'] = date_br($row['followup_date'] ?? null);
            $row['validity_limit_date_br'] = date_br($row['validity_limit_date'] ?? null);
            $row['proposal_total_br'] = money_br((float) ($row['proposal_total'] ?? 0));
            return $row;
        }, $records);

        $this->json(['records' => $records]);
    }
}