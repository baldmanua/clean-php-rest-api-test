<?php

namespace App\Controller;

abstract class BaseController
{
    protected function getRequestData(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    protected function jsonResponse(array $response, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

}