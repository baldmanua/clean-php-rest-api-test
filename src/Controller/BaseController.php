<?php

namespace App\Controller;

use App\Response\Response;

abstract class BaseController
{
    protected function getRequestData(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    protected function response(array $response, int $statusCode = 200): Response
    {
        return new Response($response, $statusCode);
    }

}