<?php

use App\Controller\IndexController;
use App\Controller\ProductController;

return [
    ['name' => 'index', 'method' => 'GET', 'path' => '/', 'handler' => [IndexController::class, 'index']],
    ['name' => 'create_product', 'method' => 'POST', 'path' => '/products', 'handler' => [ProductController::class, 'create']],
    ['name' => 'show_product', 'method' => 'GET', 'path' => '/products/{id}', 'handler' => [ProductController::class, 'show']],
    ['name' => 'update_product', 'method' => 'PUT', 'path' => '/products/{id}', 'handler' => [ProductController::class, 'update']],
    ['name' => 'delete_product', 'method' => 'DELETE', 'path' => '/products/{id}', 'handler' => [ProductController::class, 'delete']],
    ['name' => 'list_product', 'method' => 'GET', 'path' => '/products', 'handler' => [ProductController::class, 'list']],
];