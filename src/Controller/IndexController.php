<?php

namespace App\Controller;

class IndexController extends BaseController
{

    public function index()
    {
        $this->jsonResponse(['response' => 'Welcome']);
    }
}