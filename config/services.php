<?php

use App\Controller\IndexController;
use App\Controller\ProductController;
use App\Repository\ProductRepository;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

return [
    'env' => function () {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        return $_ENV;
    },

    'db' => function ($container) {
        $env = $container->get('env');
        return DriverManager::getConnection([
            'dbname'   => $env['DB_DATABASE'],
            'user'     => $env['DB_USER'],
            'password' => $env['DB_PASSWORD'],
            'host'     => $env['DB_HOST'],
            'port'     => $env['DB_PORT'],
            'driver'   => $env['DB_DRIVER'],
        ]);
    },

    ProductRepository::class => fn ($container) => new ProductRepository($container->get('db')),

    ProductController::class => fn($container) => new ProductController($container->get(ProductRepository::class)),
    IndexController::class => fn() => new IndexController(),
];