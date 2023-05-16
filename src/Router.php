<?php

declare(strict_types=1);

namespace Devlog;

/**
 * Minimal request router. Matches method + path pattern, extracts named segments.
 *
 * Usage:
 *   $router->add('GET', '/', fn() => ...);
 *   $router->add('GET', '/release/{id}', fn(array $p) => ...);
 *   $router->dispatch();
 */
class Router
{
    /** @var array<int, array{method: string, pattern: string, handler: callable}> */
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * Match the current request and invoke the handler.
     * Sends a 404 plain-text response if nothing matches.
     */
    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path   = '/' . trim((string) $path, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['pattern'], $path);
            if ($params !== null) {
                ($route['handler'])($params);
                return;
            }
        }

        http_response_code(404);
        echo '404 — not found.';
    }

    /**
     * Convert a pattern like /release/{id} into a regex and extract named params.
     *
     * @return array<string, string>|null Null if no match.
     */
    private function match(string $pattern, string $path): ?array
    {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        // Strip numeric keys from preg_match result
        return array_filter($matches, fn($k) => is_string($k), ARRAY_FILTER_USE_KEY);
    }
}
