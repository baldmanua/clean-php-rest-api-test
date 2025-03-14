<?php

namespace App\Routing;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use InvalidArgumentException;

class Router
{
    private RouteCollection $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function add(string $name, string $method, string $path, mixed $handler): void
    {
        if (!is_callable($handler)) {
            throw new InvalidArgumentException("Handler is not callable.");
        }

        $route = new Route(
            path: $path,
            defaults: [
                '_controller' => $handler,
            ],
            methods: $method,
        );

        $this->routes->add($name, $route);
    }

    public function dispatch(string $method, string $path)
    {
        $context = new RequestContext();
        $context->setMethod($method);
        $context->setPathInfo($path);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $parameters = $matcher->match($path);

            $controller = $parameters['_controller'];
            unset($parameters['_controller']);
            unset($parameters['_route']);
            return call_user_func($controller, ...$parameters);
        } catch (RouteNotFoundException $e) {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            exit;
        }
    }
}