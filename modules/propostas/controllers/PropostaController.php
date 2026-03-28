<?php
declare(strict_types=1);

final class PropostaController extends BaseController
{
    private function formDependencies(): array
    {
        return [
            'administradoras' => Administradora::active(),
            'servicos' => Servico::active(),
            'formasEnvio' => FormaEnvio::active(),
            'statusRetorno' => StatusRetorno::active(),
        ];
    }

    private function listFilters(): array
    {
        return [
            'q' => trim((string) $this->get('q', '')),
            'administradora_id' => (int) $this->get('administradora_id', 0),
            'service_id' => (int) $this->get('service_id', 0),
            'response_status_id' => (int) $this->get('response_status_id', 0),
            'send_method_id' => (int) $this->get('send_method_id', 0),
            'year' => (int) $this->get('year', 0),
            'date_from' => (string) $this->get('date_from', ''),
            'date_to' => (string) $this->get('date_to', ''),
        ];
    }

    private function loadProposalPageData(int $proposalId): array
    {
        $proposal = Proposta::find($proposalId);
        if (!$proposal) {
            http_response_code(404);
            (new ErrorController())->notFound();
            exit;
        }

        return [
            'proposal' => $proposal,
            'attachments' => PropostaAnexo::byProposal($proposalId),
            'history' => PropostaHistorico::byProposal($proposalId),
        ];
    }

    private function recordHistory(int $proposalId, string $action, string $summary, array $payload = []): void
    {
        $user = auth_user();
        if (!$user) {
            return;
        }

        PropostaHistorico::create([
            'proposta_id' => $proposalId,
            'user_id' => (int) $user['id'],
            'user_email' => (string) $user['email'],
            'action' => $action,
            'summary' => $summary,
            'payload_json' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    public function index(): void
    {
        require_auth();
        require_enabled_module('propostas');
        $filters = $this->listFilters();
        $page = max(1, (int) $this->get('page', 1));
        $perPage = max(5, min(100, (int) $this->get('per_page', 15)));
        $result = Proposta::paginateFiltered($filters, $page, $perPage);

        $this->view('propostas/views/propostas/index', [
            'title' => 'Controle de Propostas',
            'currentRoute' => 'propostas',
            'propostas' => $result['items'],
            'pagination' => $result,
            'filters' => $filters,
            'filterOptions' => [
                'administradoras' => Administradora::active(),
                'servicos' => Servico::active(),
                'formasEnvio' => FormaEnvio::active(),
                'statusRetorno' => StatusRetorno::active(),
                'years' => Proposta::yearsAvailable(),
            ],
        ]);
    }

    public function exportCsv(): void
    {
        require_auth();

        $filters = $this->listFilters();
        $items = Proposta::allFiltered($filters);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="propostas_' . date('Ymd_His') . '.csv"');

        $out = fopen('php://output', 'wb');
        if ($out === false) {
            exit;
        }

        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, [
    'Numero',
    'Data',
    'Cliente',
    'Indicação',
    'Administradora',
    'Servico',
    'Solicitante',
    'Telefone',
    'Email',
    'Forma de envio',
    'Status',
    'Valor proposta',
    'Valor fechado',
    'Follow-up',
    'Validade em dias',
    'Anexos',
], ';');

        foreach ($items as $item) {
    fputcsv($out, [
        (int) $item['id'],
        date_br($item['proposal_date']),
        $item['client_name'],
        (int) ($item['has_referral'] ?? 0) === 1 ? ($item['referral_name'] ?: 'Sim') : 'Não',
        $item['administradora_name'],
        $item['service_name'],
        $item['requester_name'],
        $item['requester_phone'],
        $item['contact_email'],
        $item['send_method_name'],
        $item['status_name'],
        number_format((float) $item['proposal_total'], 2, ',', '.'),
        $item['closed_total'] !== null ? number_format((float) $item['closed_total'], 2, ',', '.') : '',
        date_br($item['followup_date']),
        (int) $item['validity_days'],
        (int) ($item['attachment_count'] ?? 0),
    ], ';');
}

        fclose($out);
        exit;
    }

    public function create(): void
    {
        require_auth();
        $this->view('propostas/views/propostas/create', array_merge([
            'title' => 'Nova Proposta',
            'currentRoute' => 'propostas',
            'proposal' => null,
            'errors' => $_SESSION['form_errors'] ?? [],
            'old' => $_SESSION['form_old'] ?? [],
        ], $this->formDependencies()));
        unset($_SESSION['form_errors'], $_SESSION['form_old']);
    }

    public function store(): void
    {
        require_auth();
        $this->validateCsrfOrFail();

        $payload = PropostaService::payloadFromRequest($_POST);
        $payload['created_by'] = auth_id();
        $payload['updated_by'] = auth_id();
        $errors = PropostaService::validate($payload);

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old'] = $_POST;
            redirect('/propostas/nova');
        }

        $id = Proposta::create($payload);
        $user = auth_user();
        app_log((int) $user['id'], $user['email'], 'create_proposta', 'propostas', $id, 'Cadastro de nova proposta - Número da Proposta #' . $id);
        $this->recordHistory($id, 'create', 'Proposta cadastrada.', ['snapshot' => Proposta::snapshotForHistory($id)]);
        $_SESSION['flash_success'] = 'Proposta cadastrada com sucesso. Agora você já pode anexar PDFs.';
        redirect('/propostas/' . $id);
    }

    public function show(string $id): void
    {
        require_auth();
        $proposalId = (int) $id;
        $data = $this->loadProposalPageData($proposalId);

        $this->view('propostas/views/propostas/show', [
            'title' => 'Visualizar Proposta',
            'currentRoute' => 'propostas',
            'proposal' => $data['proposal'],
            'attachments' => $data['attachments'],
            'history' => $data['history'],
        ]);
    }

    public function printView(string $id): void
    {
        require_auth();
        $proposalId = (int) $id;
        $data = $this->loadProposalPageData($proposalId);

        View::render('propostas/views/propostas/print', [
            'title' => 'Impressão da Proposta #' . $proposalId,
            'proposal' => $data['proposal'],
            'attachments' => $data['attachments'],
            'history' => $data['history'],
        ], 'layouts/print');
    }

    public function edit(string $id): void
    {
        require_auth();
        $proposalId = (int) $id;
        $data = $this->loadProposalPageData($proposalId);

        $this->view('propostas/views/propostas/edit', array_merge([
            'title' => 'Editar Proposta',
            'currentRoute' => 'propostas',
            'proposal' => $data['proposal'],
            'attachments' => $data['attachments'],
            'history' => $data['history'],
            'errors' => $_SESSION['form_errors'] ?? [],
            'old' => $_SESSION['form_old'] ?? [],
        ], $this->formDependencies()));
        unset($_SESSION['form_errors'], $_SESSION['form_old']);
    }

    public function update(string $id): void
    {
        require_auth();
        $this->validateCsrfOrFail();

        $proposalId = (int) $id;
        $existing = Proposta::find($proposalId);
        if (!$existing) {
            $_SESSION['flash_error'] = 'Proposta não encontrada.';
            redirect('/propostas');
        }

        $before = Proposta::snapshotForHistory($proposalId);
        $payload = PropostaService::payloadFromRequest($_POST);
        $payload['updated_by'] = auth_id();
        $errors = PropostaService::validate($payload);

        if ($errors) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old'] = $_POST;
            redirect('/propostas/' . $proposalId . '/editar');
        }

        Proposta::update($proposalId, $payload);
        $after = Proposta::snapshotForHistory($proposalId);
        $changes = Proposta::humanDiff($before, $after);

        $user = auth_user();
        app_log((int) $user['id'], $user['email'], 'update_proposta', 'propostas', $proposalId, 'Edição de proposta - Número da Proposta #' . $proposalId);

        if ($changes) {
            $summary = 'Proposta atualizada (' . count($changes) . ' alteração(ões)).';
            $this->recordHistory($proposalId, 'update', $summary, ['changes' => $changes]);
        }

        $_SESSION['flash_success'] = 'Proposta atualizada com sucesso.';
        redirect('/propostas/' . $proposalId);
    }

    public function delete(string $id): void
    {
        require_auth();
        $this->validateCsrfOrFail();

        $proposalId = (int) $id;
        $existing = Proposta::find($proposalId);
        if ($existing) {
            Proposta::delete($proposalId);
            $user = auth_user();
            app_log((int) $user['id'], $user['email'], 'delete_proposta', 'propostas', $proposalId, 'Exclusão de proposta - Número da Proposta #' . $proposalId);
            $_SESSION['flash_success'] = 'Proposta excluída com sucesso.';
        }

        redirect('/propostas');
    }

    public function uploadAttachment(string $id): void
    {
        require_auth();
        $this->validateCsrfOrFail();

        $proposalId = (int) $id;
        $proposal = Proposta::find($proposalId);
        if (!$proposal) {
            $_SESSION['flash_error'] = 'Proposta não encontrada.';
            redirect('/propostas');
        }

        $file = $_FILES['attachment_pdf'] ?? null;
        $errors = PropostaService::attachmentValidation($file);

        if ($errors) {
            $_SESSION['flash_error'] = implode(' ', $errors);
            redirect('/propostas/' . $proposalId);
        }

        $originalName = (string) $file['name'];
        $safeOriginal = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $originalName) ?: 'arquivo.pdf';
        $storedName = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.pdf';
        $relativeDir = 'storage/uploads/propostas/' . $proposalId;
        $absoluteDir = STORAGE_PATH . '/uploads/propostas/' . $proposalId;

        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            $_SESSION['flash_error'] = 'Não foi possível preparar a pasta de anexos.';
            redirect('/propostas/' . $proposalId);
        }

        $relativePath = $relativeDir . '/' . $storedName;
        $absolutePath = ROOT_PATH . '/' . $relativePath;

        if (!move_uploaded_file((string) $file['tmp_name'], $absolutePath)) {
            $_SESSION['flash_error'] = 'Falha ao salvar o arquivo enviado.';
            redirect('/propostas/' . $proposalId);
        }

        $mimeType = 'application/pdf';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mimeType = (string) finfo_file($finfo, $absolutePath);
                finfo_close($finfo);
            }
        }

        $attachmentId = PropostaAnexo::create([
            'proposta_id' => $proposalId,
            'original_name' => $safeOriginal,
            'stored_name' => $storedName,
            'relative_path' => $relativePath,
            'mime_type' => $mimeType,
            'file_size' => (int) filesize($absolutePath),
            'uploaded_by' => (int) auth_id(),
        ]);

        $user = auth_user();
        app_log((int) $user['id'], $user['email'], 'upload_attachment', 'proposta_attachments', $attachmentId, 'Upload de anexo PDF na proposta #' . $proposalId);
        $this->recordHistory($proposalId, 'attachment_upload', 'PDF anexado à proposta.', [
            'attachment_id' => $attachmentId,
            'original_name' => $safeOriginal,
        ]);

        $_SESSION['flash_success'] = 'Anexo enviado com sucesso.';
        redirect('/propostas/' . $proposalId);
    }

    public function downloadAttachment(string $id, string $attachmentId): void
    {
        require_auth();

        $proposalId = (int) $id;
        $attachment = PropostaAnexo::findByProposal($proposalId, (int) $attachmentId);

        if (!$attachment) {
            http_response_code(404);
            echo 'Anexo não encontrado.';
            exit;
        }

        $absolutePath = ROOT_PATH . '/' . $attachment['relative_path'];
        if (!is_file($absolutePath)) {
            http_response_code(404);
            echo 'Arquivo não encontrado no servidor.';
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Length: ' . (string) filesize($absolutePath));
        header('Content-Disposition: inline; filename="' . basename((string) $attachment['original_name']) . '"');
        readfile($absolutePath);
        exit;
    }

    public function deleteAttachment(string $id, string $attachmentId): void
    {
        require_auth();
        $this->validateCsrfOrFail();

        $proposalId = (int) $id;
        $attachment = PropostaAnexo::findByProposal($proposalId, (int) $attachmentId);

        if (!$attachment) {
            $_SESSION['flash_error'] = 'Anexo não encontrado.';
            redirect('/propostas/' . $proposalId);
        }

        $absolutePath = ROOT_PATH . '/' . $attachment['relative_path'];
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }

        PropostaAnexo::delete((int) $attachmentId);
        $user = auth_user();
        app_log((int) $user['id'], $user['email'], 'delete_attachment', 'proposta_attachments', (int) $attachmentId, 'Exclusão de anexo PDF da proposta #' . $proposalId);
        $this->recordHistory($proposalId, 'attachment_delete', 'PDF removido da proposta.', [
            'attachment_id' => (int) $attachmentId,
            'original_name' => $attachment['original_name'],
        ]);

        $_SESSION['flash_success'] = 'Anexo removido com sucesso.';
        redirect('/propostas/' . $proposalId);
    }
}
