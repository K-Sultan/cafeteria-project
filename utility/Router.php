<?php

class Router
{
    private $routes = [];

    private function register($path, $method, $handler)
    {
        // Convert something like /users/edit/:id to a regex pattern: ^/users/edit/([^/]+)$
        $regex = preg_replace('/:[a-zA-Z0-9_]+/', '([^/]+)', $path);
        $regex = "@^" . $regex . "$@";

        $this->routes[$method][$path] = [
            'handler' => $handler,
            'regex' => $regex
        ];
    }

    public function get($path, $handler)
    {
        $this->register($path, 'GET', $handler);
    }
    public function post($path, $handler)
    {
        $this->register($path, 'POST', $handler);
    }
    public function delete($path, $handler)
    {
        $this->register($path, 'DELETE', $handler);
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Method Spoofing
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofedMethod = strtoupper(trim($_POST['_method']));
            if (in_array($spoofedMethod, ['PUT', 'PATCH', 'DELETE'], true)) {
                $method = $spoofedMethod;
            }
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove project subfolder if exists (as per your original code)
        $path = str_replace("/cafeteria", "", $path);

        // Iterate through routes for the current method
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routePath => $route) {
                if (preg_match($route['regex'], $path, $matches)) {
                    // Remove the full match (index 0) to keep only the parameters
                    array_shift($matches);

                    $class = $route['handler'][0];
                    $func = $route['handler'][1];

                    if (class_exists($class) && method_exists($class, $func)) {
                        $instance = new $class();
                        // Call the method and pass captured parameters (like $id)
                        return call_user_func_array([$instance, $func], $matches);
                    }
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
