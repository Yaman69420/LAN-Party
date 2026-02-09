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

        // Zoek route
        if (isset($this->routes[$requestMethod][$path])) {
            $route = $this->routes[$requestMethod][$path];
            $controllerName = 'App\\Controllers\\' . $route['controller'];
            $method = $route['method'];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $method)) {
                    $controller->$method();
                    return;
                }
            }
        }

        // 404 - Route niet gevonden
        http_response_code(404);
        require __DIR__ . '/../Views/404.php';
    }
}
