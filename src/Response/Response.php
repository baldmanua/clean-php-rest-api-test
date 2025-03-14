<?php

namespace App\Response;

use JetBrains\PhpStorm\NoReturn;

readonly class Response
{
    public function __construct(
        public array $data = [],
        public int   $statusCode = 200,
    )
    {
    }

    #[NoReturn]
    public function outputJson(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        echo json_encode($this->data);
        exit;
    }
}