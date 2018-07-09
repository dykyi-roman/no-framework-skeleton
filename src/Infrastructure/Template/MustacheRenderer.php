<?php

namespace Dykyi\Infrastructure\Template;

use Mustache_Engine;

/**
 * Class MustacheRenderer
 * @package Dykyi\Infrastructure\Template
 */
class MustacheRenderer implements Renderer
{
    /** @var Mustache_Engine  */
    private $engine;

    public function __construct(Mustache_Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @inheritdoc
     */
    public function render($template, $data = []) : string
    {
        return $this->engine->render($template, $data);
    }
}