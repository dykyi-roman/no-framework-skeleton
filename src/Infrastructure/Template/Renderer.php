<?php declare(strict_types = 1);

namespace Dykyi\Infrastructure\Template;

interface Renderer
{
    /**
     * @param $template
     * @param array $data
     * @return string
     */
    public function render($template, $data = []) : string;
}