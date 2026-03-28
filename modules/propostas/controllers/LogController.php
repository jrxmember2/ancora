<?php
declare(strict_types=1);

final class LogController extends BaseController
{
    public function index(): void
    {
        require_auth();
        require_superadmin();
        require_enabled_module('logs');

        $filters = [
            'q' => trim((string) $this->get('q', '')),
            'action' => trim((string) $this->get('action', '')),
            'user_email' => trim((string) $this->get('user_email', '')),
            'date_from' => trim((string) $this->get('date_from', '')),
            'date_to' => trim((string) $this->get('date_to', '')),
        ];
        $page = max(1, (int) $this->get('page', 1));
        $perPage = max(10, min(200, (int) $this->get('per_page', 50)));
        $pagination = AuditLog::paginateFiltered($filters, $page, $perPage);

        $this->view('propostas/views/logs/index', [
            'title' => 'Logs do Sistema',
            'currentRoute' => 'logs',
            'logs' => $pagination['items'],
            'pagination' => $pagination,
            'filters' => $filters,
            'actions' => AuditLog::distinctActions(),
            'users' => AuditLog::distinctUsers(),
        ]);
    }
}
