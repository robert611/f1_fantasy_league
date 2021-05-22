<?php 

use App\Model\Router\RouteParameters;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file): self
    {
        $router = new static;

        require $file;

        return $router;
    }

    public function get(string $uri, string $controller, string $method): void
    {
        $this->routes['GET'][$uri] = ['controller' => $controller, 'method' => $method];
    }

    public function post(string $uri, string $controller, string $method): void
    {
        $this->routes['POST'][$uri] = ['controller' => $controller, 'method' => $method];
    }

    public function direct(string $uri, string $requestType): void
    {
        $uri = "/$uri";

        $routeParamatersClass = new RouteParameters();

        $routeData = $routeParamatersClass->getRawUriAndMatchingRoute($uri, $this->routes[$requestType]);

        if (is_array($routeData))
        {
            $route = $routeData['route'];
            
            $controllerClassName = $route['controller'];

            require './src/Controller/' . $controllerClassName . '.php';

            $controllerName = 'App\Controller\\' . $controllerClassName;

            $controller = new $controllerName();

            $method = $route['method'];

            $parameters = $routeParamatersClass->getParameters($uri, $this->routes[$requestType]);

            $controller->$method(implode($parameters));

            return;
        }

        require './src/Controller/ErrorController.php';

        $controller = new App\Controller\ErrorController();

        $controller->notFound();
    }
}