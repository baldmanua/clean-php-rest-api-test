<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Routing\Router;
use App\Container;

$services = require __DIR__ . '/../config/services.php';
$container = new Container($services);

$router = new Router();
$routes = require __DIR__ . '/../config/routes.php';

foreach ($routes as $route) {
    try {
        $handler = [
            $container->get($route['handler'][0]),
            $route['handler'][1]
        ];

        $router->add($route['name'], $route['method'], $route['path'], $handler);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

$response = $router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($response instanceof App\Response\Response) {
    $response->outputJson();
} else {
    echo $response;
}