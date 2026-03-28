<?php
declare(strict_types=1);

final class SearchController extends BaseController
{
    public function index(): void
    {
        require_auth();
        require_enabled_module('busca');

        $q = trim((string) $this->get('q', ''));
        $results = [
            'propostas' => [],
            'usuarios' => [],
            'logs' => [],
        ];

        if ($q !== '') {
            $results['propostas'] = Proposta::globalSearch($q, 12);
            if (is_superadmin()) {
                $results['usuarios'] = User::globalSearch($q, 8);
                $results['logs'] = AuditLog::globalSearch($q, 12);
            }
        }

        $this->view('search/index', [
            'title' => 'Busca Global',
            'currentRoute' => 'busca',
            'searchTerm' => $q,
            'results' => $results,
        ]);
    }
}
