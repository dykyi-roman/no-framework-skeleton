<?php

namespace Dykyi\Infrastructure;

use Dykyi\Infrastructure\Controllers\DefaultController;

/**
 * Class Routers
 * @package Dykyi\Infrastructure
 */
class Routers
{
    public static function get()
    {
        return [
            ['GET', '/', [DefaultController::class, 'index']],
        ];
    }
}