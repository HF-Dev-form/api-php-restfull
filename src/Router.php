<?php

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    public function get($pattern, $controllerAction)
    {
        $this->addRoute('GET', $pattern, $controllerAction);
    }

    public function post($pattern, $controllerAction)
    {
        $this->addRoute('POST', $pattern, $controllerAction);
    }

    public function put($pattern, $controllerAction)
    {
        $this->addRoute('PUT', $pattern, $controllerAction);
    }

    public function delete($pattern, $controllerAction)
    {
        $this->addRoute('DELETE', $pattern, $controllerAction);
    }

    private function addRoute($method, $pattern, $controllerAction)
    {
        $this->routes[$method][$this->createPattern($pattern)] = $controllerAction;
    }

    private function createPattern($pattern)
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    public function dispatch($requestUri, $requestMethod)
    {
        $requestUri = parse_url($requestUri, PHP_URL_PATH);
        $routes = $this->routes[$requestMethod];

        foreach ($routes as $pattern => $controllerAction) {
            if (preg_match($pattern, $requestUri, $matches)) {
                $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                list($controller, $action) = explode('@', $controllerAction);
                $controller = "App\\Controller\\$controller";
                require_once __DIR__ . "/Controller/" . explode('@', $controllerAction)[0] . ".php";
                $controllerInstance = new $controller();
                call_user_func_array([$controllerInstance, $action], $matches);
                return;
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo '404 - Page not found';
    }
}
?>