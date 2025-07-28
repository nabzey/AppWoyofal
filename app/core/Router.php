<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, callable $handler)
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function dispatch(string $method, string $path)
    {
        $method = strtoupper($method);
        if (isset($this->routes[$method][$path])) {
            return call_user_func($this->routes[$method][$path]);
        }
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Route non trouvée'
        ];
    }
}
            