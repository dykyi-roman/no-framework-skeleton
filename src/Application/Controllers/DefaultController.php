<?php

namespace Dykyi\Application\Controllers;

use Mustache_Engine;
use Dykyi\Infrastructure\Containers;
use Dykyi\Infrastructure\Template\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Dykyi\Infrastructure\Controllers
 */
class DefaultController
{
    /** @var Request */
    private $request;

    /** @var Containers */
    private $containers;

    /** @var Renderer */
    private $engine;

    public function __construct(Request $request, Containers $containers)
    {
        $this->containers = $containers;
        $this->request = $request;
        $this->engine = $containers->get(Mustache_Engine::class);
    }

    public function index()
    {
        $html = $this->engine->render('default/index', ['name' => 'test']);
        return Response::create($html);
    }
}