<?php

namespace Building\Infrastructure\Controllers;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        $this->response->setContent('test');
        return $this->response;
    }
}