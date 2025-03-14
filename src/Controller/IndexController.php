<?php

namespace App\Controller;

use App\Response\Response;

class IndexController extends BaseController
{

    public function index(): Response
    {
        return $this->response(['response' => 'Welcome']);
    }
}