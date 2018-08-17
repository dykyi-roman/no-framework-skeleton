<?php

namespace Dykyi\Infrastructure\Template;

interface TemplateInterface
{
    /**
     * @param $template
     * @param array $data
     * @return string
     */
    public function render($template, array $data = []): string;

    /**
     * @param TemplateInterface $template
     * @return mixed
     */
    public function configuration(string $path);
}