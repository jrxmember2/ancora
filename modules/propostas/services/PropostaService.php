<?php
declare(strict_types=1);

final class PropostaService
{
    public static function validate(array $input): array
    {
        $errors = [];

        if (empty($input['proposal_date'])) {
            $errors[] = 'Informe a data.';
        }
        if (empty(trim((string) $input['client_name']))) {
            $errors[] = 'Informe o cliente.';
        }
        if (empty($input['administradora_id'])) {
            $errors[] = 'Selecione a administradora.';
        }
        if (empty($input['service_id'])) {
            $errors[] = 'Selecione o serviço.';
        }
        if ($input['proposal_total'] === null) {
            $errors[] = 'Informe o valor da proposta.';
        }
        if (empty(trim((string) $input['requester_name']))) {
            $errors[] = 'Informe o solicitante.';
        }
        if (empty($input['send_method_id'])) {
            $errors[] = 'Selecione a forma de envio.';
        }
        if (empty($input['response_status_id'])) {
            $errors[] = 'Selecione o status do retorno.';
        }

        if (!empty($input['contact_email']) && !filter_var((string) $input['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Informe um e-mail válido.';
        }

        if (!empty($input['validity_days']) && (int) $input['validity_days'] < 0) {
            $errors[] = 'A validade não pode ser negativa.';
        }

        if ((int) ($input['has_referral'] ?? 0) === 1 && empty(trim((string) ($input['referral_name'] ?? '')))) {
            $errors[] = 'Informe o nome da indicação.';
        }

        $status = !empty($input['response_status_id']) ? StatusRetorno::find((int) $input['response_status_id']) : null;
        if ($status && (int) $status['requires_closed_value'] === 1 && $input['closed_total'] === null) {
            $errors[] = 'O valor total fechado é obrigatório para status aprovado.';
        }
        if ($status && (int) $status['requires_refusal_reason'] === 1 && empty(trim((string) $input['refusal_reason']))) {
            $errors[] = 'O motivo da recusa é obrigatório para status declinada.';
        }

        return $errors;
    }

    public static function payloadFromRequest(array $request): array
    {
        $hasReferral = (int) (($request['has_referral'] ?? 0) ? 1 : 0);
        $referralName = trim((string) ($request['referral_name'] ?? ''));

        return [
            'proposal_date' => $request['proposal_date'] ?? null,
            'client_name' => trim((string) ($request['client_name'] ?? '')),
            'administradora_id' => (int) ($request['administradora_id'] ?? 0),
            'service_id' => (int) ($request['service_id'] ?? 0),
            'proposal_total' => money_to_db((string) ($request['proposal_total'] ?? '')),
            'closed_total' => money_to_db((string) ($request['closed_total'] ?? '')),
            'requester_name' => trim((string) ($request['requester_name'] ?? '')),
            'requester_phone' => trim((string) ($request['requester_phone'] ?? '')),
            'contact_email' => trim((string) ($request['contact_email'] ?? '')),
            'has_referral' => $hasReferral,
            'referral_name' => $hasReferral === 1 ? ($referralName ?: null) : null,
            'send_method_id' => (int) ($request['send_method_id'] ?? 0),
            'response_status_id' => (int) ($request['response_status_id'] ?? 0),
            'refusal_reason' => trim((string) ($request['refusal_reason'] ?? '')) ?: null,
            'followup_date' => !empty($request['followup_date']) ? $request['followup_date'] : null,
            'validity_days' => max(0, (int) ($request['validity_days'] ?? 0)),
            'notes' => trim((string) ($request['notes'] ?? '')) ?: null,
        ];
    }

    public static function attachmentValidation(?array $file): array
    {
        $errors = [];

        if (!$file || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Selecione um arquivo PDF.';
            return $errors;
        }

        $error = (int) ($file['error'] ?? UPLOAD_ERR_OK);
        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = 'Falha no upload do arquivo.';
            return $errors;
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0) {
            $errors[] = 'Arquivo inválido.';
        }
        if ($size > 8 * 1024 * 1024) {
            $errors[] = 'O arquivo deve ter no máximo 8 MB.';
        }

        $name = (string) ($file['name'] ?? '');
        if (!preg_match('/\.pdf$/i', $name)) {
            $errors[] = 'Apenas arquivos PDF são permitidos.';
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName !== '' && is_file($tmpName) && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? (string) finfo_file($finfo, $tmpName) : '';
            if ($finfo) {
                finfo_close($finfo);
            }

            if ($mime !== '' && !in_array($mime, ['application/pdf', 'application/x-pdf'], true)) {
                $errors[] = 'O arquivo enviado não parece ser um PDF válido.';
            }
        }

        return $errors;
    }
}