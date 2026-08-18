<?php

function base_url(string $path = ''): string
{
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

    if ($basePath === '/' || $basePath === '.') {
        $basePath = '';
    }

    return $basePath . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return base_url('public/' . ltrim($path, '/'));
}

function current_path(): string
{
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

    if ($basePath !== '/' && $basePath !== '.') {
        if (str_starts_with($requestPath, $basePath)) {
            $requestPath = substr($requestPath, strlen($basePath));
        }
    }

    $requestPath = '/' . trim($requestPath, '/');

    return $requestPath === '//' ? '/' : $requestPath;
}

function is_active(string $path): bool
{
    return current_path() === $path;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}