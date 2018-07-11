<?php

namespace Dykyi\Application;

use Dykyi\Application\Controllers\DefaultController;

/**
 * Class Routers
 *
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