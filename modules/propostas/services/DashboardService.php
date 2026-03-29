<?php
declare(strict_types=1);

final class DashboardService
{
    public static function summary(int $year): array
    {
        $pdo = Database::connection();

        $monthlyStmt = $pdo->prepare('
            SELECT MONTH(proposal_date) AS month_num, COUNT(*) AS total
            FROM propostas
            WHERE YEAR(proposal_date) = :year
            GROUP BY MONTH(proposal_date)
            ORDER BY month_num
        ');
        $monthlyStmt->execute(['year' => $year]);
        $monthlyRaw = $monthlyStmt->fetchAll();

        $months = [1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'];
        $monthlyMap = array_fill(1, 12, 0);
        foreach ($monthlyRaw as $row) {
            $monthlyMap[(int) $row['month_num']] = (int) $row['total'];
        }

        $serviceStmt = $pdo->prepare('
            SELECT s.name, COUNT(*) AS total
            FROM propostas p
            INNER JOIN servicos s ON s.id = p.service_id
            WHERE YEAR(p.proposal_date) = :year
            GROUP BY s.id, s.name
            ORDER BY total DESC
        ');
        $serviceStmt->execute(['year' => $year]);
        $services = $serviceStmt->fetchAll();

        $adminStmt = $pdo->prepare('
            SELECT a.name, COUNT(*) AS total
            FROM propostas p
            INNER JOIN administradoras a ON a.id = p.administradora_id
            WHERE YEAR(p.proposal_date) = :year
            GROUP BY a.id, a.name
            ORDER BY total DESC
        ');
        $adminStmt->execute(['year' => $year]);
        $admins = $adminStmt->fetchAll();

        $totalStmt = $pdo->prepare('
            SELECT
                COALESCE(SUM(p.proposal_total), 0) AS total_amount,
                COALESCE(SUM(
                    CASE
                        WHEN st.system_key = "approved" THEN COALESCE(p.closed_total, 0)
                        ELSE 0
                    END
                ), 0) AS total_closed_amount,
                COALESCE(SUM(
                    CASE
                        WHEN st.system_key = "declined" THEN COALESCE(p.proposal_total, 0)
                        ELSE 0
                    END
                ), 0) AS total_declined_amount
            FROM propostas p
            INNER JOIN status_retorno st ON st.id = p.response_status_id
            WHERE YEAR(p.proposal_date) = :year
        ');
        $totalStmt->execute(['year' => $year]);
        $totals = $totalStmt->fetch();

        $alerts = array_merge(Proposta::overdueFollowups(), Proposta::expiringFollowups());
        $validityAlerts = Proposta::validityAlertsDue();

        return [
            'year' => $year,
            'years' => Proposta::yearsAvailable(),
            'monthly_labels' => array_values($months),
            'monthly_values' => array_values($monthlyMap),
            'services_labels' => array_column($services, 'name'),
            'services_values' => array_map('intval', array_column($services, 'total')),
            'admins_labels' => array_column($admins, 'name'),
            'admins_values' => array_map('intval', array_column($admins, 'total')),
            'total_amount' => (float) ($totals['total_amount'] ?? 0),
            'total_closed_amount' => (float) ($totals['total_closed_amount'] ?? 0),
            'total_declined_amount' => (float) ($totals['total_declined_amount'] ?? 0),
            'alerts' => $alerts,
            'validity_alerts' => $validityAlerts,
        ];
    }
}