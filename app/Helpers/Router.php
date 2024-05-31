<?php
namespace App\Helpers;

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function put($path, $callback) {
        $this->routes['PUT'][$path] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        $callback = $this->routes[$method][$path] ?? null;

        // Busca la ruta correspondiente
        foreach ($this->routes[$method] as $route => $handler) {
            // Reemplaza las llaves {} con \d+ para capturar parámetros dinámicos
            $pattern = preg_replace('/{[^}]+}/', '\\d+', $route);
            
            // Si la ruta coincide con la expresión regular
            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                $callback = $handler;
                // Elimina el primer elemento (corresponde a la ruta completa)
                array_shift($matches);
                // Llama al controlador con los parámetros capturados
                call_user_func_array($callback, $matches);
                break;
            }
        }

        if (!$callback) {
            echo "Error 404: Not Found";
        } 
    }
}