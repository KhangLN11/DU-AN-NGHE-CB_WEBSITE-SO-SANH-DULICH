<?php

class Controller
{
    protected function view(
        string $view,
        array $data = [],
        string $layout = 'layouts/main'
    ): void {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../views/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception('Không tìm thấy view: ' . $view);
        }

        if (!file_exists($layoutPath)) {
            throw new Exception('Không tìm thấy layout: ' . $layout);
        }

        extract($data, EXTR_SKIP);

        ob_start();

        require $viewPath;

        $content = ob_get_clean();

        require $layoutPath;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . base_url($path));
        exit;
    }
}