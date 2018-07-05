<?php

namespace Dykyi\Infrastructure\Controllers;

use Dykyi\Infrastructure\Service\Containers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Dykyi\Infrastructure\Controllers
 */
class DefaultController
{
    /** @var Request  */
    private $request;

    /** @var Containers */
    private $containers;

    /**
     * DefaultController constructor.
     * @param Request $request
     * @param Containers $containers
     */
    public function __construct(Request $request, Containers $containers)
    {
        $this->containers = $containers;
        $this->request = $request;
    }

    public function index()
    {
        return Response::create(__CLASS__);
    }
}