<?php
declare(strict_types=1);

final class HomeController extends BaseController
{
    public function index(): void
    {
        require_auth();

        $catalog = require ROOT_PATH . '/app/config/module_catalog.php';
        $databaseModules = [];
        $authUser = auth_user();

        foreach (Module::all() as $module) {
            $databaseModules[$module['slug']] = $module;
        }

        $modules = [];

        foreach ($catalog as $item) {
            if (($item['slug'] ?? '') === 'logs') {
                continue;
            }

            $db = $databaseModules[$item['slug']] ?? null;
            $isInstalled = $db !== null;
            $isEnabled = $db ? (int) ($db['is_enabled'] ?? 0) === 1 : (int) ($item['is_available'] ?? 0) === 1;
            $isSuperadminOnly = (int) ($item['superadmin_only'] ?? 0) === 1;
            $hasAccess = $isSuperadminOnly ? is_superadmin() : user_has_module_access($item['slug'], $authUser);

            if (!$hasAccess) {
                continue;
            }

            $status = 'planned';
            $statusLabel = 'Em breve';

            if ($isInstalled && $isEnabled) {
                $status = 'active';
                $statusLabel = 'Disponível';
            } elseif ($isInstalled && !$isEnabled) {
                $status = 'inactive';
                $statusLabel = 'Desativado';
            } elseif (!$isInstalled && ((int) ($item['is_available'] ?? 0) === 1)) {
                $status = 'available';
                $statusLabel = 'Preparado';
            }

            $modules[] = [
                'slug' => $item['slug'],
                'name' => $db['name'] ?? $item['name'],
                'short_name' => $item['short_name'] ?? ($db['name'] ?? $item['name']),
                'icon_class' => $db['icon_class'] ?? $item['icon_class'],
                'route_prefix' => $db['route_prefix'] ?? $item['route_prefix'],
                'description' => $item['description'] ?? '',
                'accent' => $item['accent'] ?? 'primary',
                'status' => $status,
                'status_label' => $statusLabel,
                'is_installed' => $isInstalled,
                'is_enabled' => $isEnabled,
                'has_real_link' => $status === 'active',
            ];
        }

        usort($modules, static function (array $a, array $b): int {
            $orderA = $a['has_real_link'] ? 0 : 1;
            $orderB = $b['has_real_link'] ? 0 : 1;
            return [$orderA, $a['name']] <=> [$orderB, $b['name']];
        });

        $this->view('home/index', [
            'title' => 'Hub',
            'currentRoute' => 'hub',
            'modules' => $modules,
            'moduleStats' => [
                'available' => count(array_filter($modules, static fn(array $module): bool => $module['has_real_link'])),
                'planned' => count(array_filter($modules, static fn(array $module): bool => !$module['has_real_link'])),
                'visible' => count($modules),
            ],
        ]);
    }
}
