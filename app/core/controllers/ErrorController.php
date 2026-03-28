<?php
declare(strict_types=1);

final class ErrorController extends BaseController
{
    public function notFound(): void
    {
        View::render('errors/404', ['title' => 'Página não encontrada']);
    }
}
