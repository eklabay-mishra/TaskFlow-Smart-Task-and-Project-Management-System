<?php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void
    {
        // Convert route parameter tokens e.g. /projects/{id} -> regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        // Strip query string
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                [$controllerName, $action] = $route['handler'];

                if (!class_exists($controllerName)) {
                    http_response_code(500);
                    die("Controller class not found: {$controllerName}");
                }

                $controller = new $controllerName();
                if (!method_exists($controller, $action)) {
                    http_response_code(500);
                    die("Action {$action} not found on controller {$controllerName}");
                }

                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // 404 Handling
        http_response_code(404);
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Resource Route Not Found']);
            exit;
        }

        require APP_ROOT . '/views/errors/404.php';
    }
}
