<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $path = $this->normalizePath($path);

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

        if ($basePath !== '/' && $basePath !== '.') {
            if (str_starts_with($requestPath, $basePath)) {
                $requestPath = substr($requestPath, strlen($basePath));
            }
        }

        $requestPath = $this->normalizePath($requestPath);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $pattern = $this->convertPathToRegex($route['path']);

            if (preg_match($pattern, $requestPath, $matches)) {
                $params = [];

                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                $this->runHandler($route['handler'], $params);
                return;
            }
        }

        http_response_code(404);

        echo '<h1>404 - Không tìm thấy trang</h1>';
    }

    private function runHandler(callable|array $handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        [$controllerName, $methodName] = $handler;

        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            throw new Exception('Không tìm thấy controller: ' . $controllerName);
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            throw new Exception('Controller không tồn tại: ' . $controllerName);
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            throw new Exception(
                'Không tìm thấy method ' . $methodName . ' trong ' . $controllerName
            );
        }

        call_user_func_array([$controller, $methodName], $params);
    }

    private function convertPathToRegex(string $path): string
    {
        $pattern = preg_replace(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            '(?P<$1>[^/]+)',
            $path
        );

        return '#^' . $pattern . '$#';
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        if ($path === '//') {
            return '/';
        }

        return $path;
    }
}