<?php

namespace Dykyi\Application\Controllers;

use Mustache_Engine;
use Dykyi\Application\Containers;
use Dykyi\Infrastructure\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Dykyi\Infrastructure\Controllers
 */
class DefaultController
{
    /** @var Renderer */
    private $engine;

    public function __construct(Containers $containers)
    {
        $this->engine = $containers->get(Mustache_Engine::class);
    }

    /**
     * @return Response
     */
    public function index()
    {
        $html = $this->engine->render('default/index', ['time' => date('h:i:s')]);
        return Response::create($html);
    }
}