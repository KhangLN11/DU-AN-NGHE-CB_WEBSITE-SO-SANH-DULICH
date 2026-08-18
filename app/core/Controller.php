<?php

class Controller
{
    protected function view(
        string $view,
        array $data = [],
        string $layout = 'main'
    ): void {
        extract($data);

        $viewFile =
            __DIR__
            . '/../views/'
            . $view
            . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception(
                'View không tồn tại: '
                . $view
            );
        }

        ob_start();

        require $viewFile;

        $content = ob_get_clean();

        $layoutFile =
            __DIR__
            . '/../views/layouts/'
            . $layout
            . '.php';

        if (!file_exists($layoutFile)) {
            throw new Exception(
                'Layout không tồn tại: '
                . $layout
            );
        }

        require $layoutFile;
    }

    protected function redirect(
        string $path = ''
    ): never {
        header(
            'Location: '
            . base_url($path)
        );

        exit;
    }
}