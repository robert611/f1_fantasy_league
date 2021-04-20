<?php 

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
        if (array_key_exists($uri, $this->routes[$requestType]))
        {
            $controllerClassName = $this->routes[$requestType][$uri]['controller'];

            require './src/Controller/' . $controllerClassName . '.php';

            $controllerName = 'App\Controller\\' . $controllerClassName;

            $controller = new $controllerName();

            $method = $this->routes[$requestType][$uri]['method'];

            $controller->$method();

            return;
        }

        require './src/Controller/ErrorController.php';

        $controller = new App\Controller\ErrorController();

        $controller->notFound();
    }
}