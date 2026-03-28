<?php
declare(strict_types=1);

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/master'): void
    {
        extract($data, EXTR_SKIP);

        $coreView = VIEW_PATH . '/' . $view . '.php';
        $moduleView = MODULES_PATH . '/' . $view . '.php';

        if (file_exists($coreView)) {
            $viewFile = $coreView;
        } elseif (file_exists($moduleView)) {
            $viewFile = $moduleView;
        } else {
            throw new RuntimeException('View não encontrada: ' . $view);
        }

        $layoutFile = VIEW_PATH . '/' . $layout . '.php';
        require $layoutFile;
    }

    public static function partial(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $coreView = VIEW_PATH . '/' . $view . '.php';
        $moduleView = MODULES_PATH . '/' . $view . '.php';
        $file = file_exists($coreView) ? $coreView : $moduleView;

        if (!file_exists($file)) {
            throw new RuntimeException('Partial não encontrada: ' . $view);
        }

        require $file;
    }
}
