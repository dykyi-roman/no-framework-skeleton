<?php

namespace Dykyi\Application\Controllers;

use Dykyi\Application\Containers;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package Dykyi\Infrastructure\Controllers
 */
class DefaultController
{
    private $engine;

    public function __construct(Containers $containers)
    {
        $this->engine = $containers->get('Template');
    }

    /**
     *
     * @return Response
     */
    public function index()
    {
        $html = $this->engine->render('default/index', ['time' => date('h:i:s')]);
        return Response::create($html);
    }
}