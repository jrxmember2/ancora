<?php
declare(strict_types=1);

final class FollowupAlertService
{
    public static function run(): array
    {
        $recipient = Setting::get('followup_alert_email', 'contato@rebecamedina.com.br');
        $followups = Proposta::followupAlertsDue();
        $validities = Proposta::validityAlertsDue();

        $followups = array_values(array_filter($followups, fn(array $item): bool => !self::alreadySentToday((int) $item['id'], 'followup', $recipient)));
        $validities = array_values(array_filter($validities, fn(array $item): bool => !self::alreadySentToday((int) $item['id'], 'validity', $recipient)));

        $groups = [];
        if ($followups) {
            $groups['followup'] = $followups;
        }
        if ($validities) {
            $groups['validity'] = $validities;
        }

        if (!$groups) {
            return ['total' => 0, 'sent' => 0, 'errors' => 0, 'followup' => 0, 'validity' => 0];
        }

        $subject = 'Alertas diários - ' . APP_NAME;
        $body = self::buildDigestBody($groups);
        $ok = Mailer::send($recipient, $subject, $body);

        $sent = 0;
        $errors = 0;

        foreach ($followups as $item) {
            self::registerDispatch((int) $item['id'], 'followup', $recipient, $ok ? 'sent' : 'error', $ok ? null : 'Falha no mail()');
            $ok ? $sent++ : $errors++;
        }

        foreach ($validities as $item) {
            self::registerDispatch((int) $item['id'], 'validity', $recipient, $ok ? 'sent' : 'error', $ok ? null : 'Falha no mail()');
            $ok ? $sent++ : $errors++;
        }

        return [
            'total' => count($followups) + count($validities),
            'sent' => $sent,
            'errors' => $errors,
            'followup' => count($followups),
            'validity' => count($validities),
        ];
    }

    private static function alreadySentToday(int $propostaId, string $alertType, string $recipient): bool
    {
        $stmt = Database::connection()->prepare(
            'SELECT id FROM notification_dispatches
             WHERE proposta_id = :proposta_id
               AND alert_type = :alert_type
               AND dispatch_date = ' . Database::currentDate() . '
               AND recipient_email = :recipient
             LIMIT 1'
        );
        $stmt->execute([
            'proposta_id' => $propostaId,
            'alert_type' => $alertType,
            'recipient' => $recipient,
        ]);

        return (bool) $stmt->fetch();
    }

    private static function registerDispatch(int $propostaId, string $alertType, string $recipient, string $status, ?string $errorMessage = null): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO notification_dispatches (proposta_id, alert_type, dispatch_date, recipient_email, dispatch_status, error_message)
             VALUES (:proposta_id, :alert_type, ' . Database::currentDate() . ', :recipient, :status, :error_message)'
        );
        $stmt->execute([
            'proposta_id' => $propostaId,
            'alert_type' => $alertType,
            'recipient' => $recipient,
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
    }

    private static function buildDigestBody(array $groups): string
    {
        $sections = [];

        if (!empty($groups['followup'])) {
            $rows = '';
            foreach ($groups['followup'] as $item) {
                $daysLeft = (int) floor((strtotime($item['followup_date']) - strtotime(date('Y-m-d'))) / 86400);
                $situation = $daysLeft < 0 ? 'Vencido' : ($daysLeft === 0 ? 'Vence hoje' : 'Faltam ' . $daysLeft . ' dia(s)');
                $rows .= '<tr>
                    <td>#' . (int) $item['id'] . '</td>
                    <td>' . htmlspecialchars($item['client_name'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars(date_br($item['followup_date']), ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($situation, ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($item['status_name'], ENT_QUOTES, 'UTF-8') . '</td>
                </tr>';
            }

            $sections[] = '<h2 style="margin:24px 0 12px;">Follow-ups críticos</h2>
                <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse:collapse;border-color:#cccccc;">
                    <thead>
                        <tr>
                            <th align="left">Proposta</th>
                            <th align="left">Cliente</th>
                            <th align="left">Follow-up</th>
                            <th align="left">Situação</th>
                            <th align="left">Status</th>
                        </tr>
                    </thead>
                    <tbody>' . $rows . '</tbody>
                </table>';
        }

        if (!empty($groups['validity'])) {
            $rows = '';
            foreach ($groups['validity'] as $item) {
                $limitDate = $item['validity_limit_date'] ?? null;
                $daysLeft = $limitDate ? (int) floor((strtotime($limitDate) - strtotime(date('Y-m-d'))) / 86400) : 0;
                $situation = $daysLeft < 0 ? 'Validade expirada' : ($daysLeft === 0 ? 'Expira hoje' : 'Expira em ' . $daysLeft . ' dia(s)');
                $rows .= '<tr>
                    <td>#' . (int) $item['id'] . '</td>
                    <td>' . htmlspecialchars($item['client_name'], ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars(date_br($item['proposal_date']), ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . (int) $item['validity_days'] . ' dia(s)</td>
                    <td>' . htmlspecialchars($limitDate ? date_br((string) $limitDate) : '-', ENT_QUOTES, 'UTF-8') . '</td>
                    <td>' . htmlspecialchars($situation, ENT_QUOTES, 'UTF-8') . '</td>
                </tr>';
            }

            $sections[] = '<h2 style="margin:24px 0 12px;">Validades de proposta em risco</h2>
                <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse:collapse;border-color:#cccccc;">
                    <thead>
                        <tr>
                            <th align="left">Proposta</th>
                            <th align="left">Cliente</th>
                            <th align="left">Data da proposta</th>
                            <th align="left">Validade</th>
                            <th align="left">Limite</th>
                            <th align="left">Situação</th>
                        </tr>
                    </thead>
                    <tbody>' . $rows . '</tbody>
                </table>';
        }

        return '<div style="font-family:Arial,sans-serif;color:#222;">
    <h1 style="margin-bottom:8px;">Alertas diários de ' . htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') . '</h1>
    <p>Segue o resumo automático das propostas que exigem atenção hoje.</p>
    ' . implode('', $sections) . '
    <p style="margin-top:24px;color:#555;">Este envio continua diariamente enquanto os status permanecerem fora das condições de parada.</p>
</div>';
    }
}
