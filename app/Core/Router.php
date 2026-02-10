<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router class - bepaalt welke controller en method aangeroepen worden
 */
class Router
{
    private array $routes = [];

    /**
     * Registreer een GET route
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Registreer een POST route
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Dispatch de huidige request naar de juiste controller
     */
    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Verwijder trailing slash (behalve bij root /)
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        // 1. Exacte match (Snelheid)
        if (isset($this->routes[$requestMethod][$path])) {
            $this->invoke($this->routes[$requestMethod][$path]);
            return;
        }

        // 2. Regex match voor dynamische routes (bijv. /user/profile/{slug})
        foreach ($this->routes[$requestMethod] ?? [] as $routePath => $routeConfig) {
            // Converteer {param} naar regex group
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $path, $matches)) {
                    // Filter numerieke keys eruit (zodat we alleen named params overhouden)
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $this->invoke($routeConfig, $params);
                    return;
                }
            }
        }

        // 404 - Route niet gevonden
        http_response_code(404);
        require __DIR__ . '/../Views/404.php';
    }

    /**
     * Hulpmethode om controller aan te roepen
     */
    private function invoke(array $route, array $params = []): void
    {
        $controllerName = 'App\\Controllers\\' . $route['controller'];
        $method = $route['method'];

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], array_values($params));
                return;
            }
        }
        
        // Fallback als methode niet bestaat
        http_response_code(404);
        require __DIR__ . '/../Views/404.php';
    }
}
