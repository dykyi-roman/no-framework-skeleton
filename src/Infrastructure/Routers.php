<?php

namespace Building\Infrastructure;

use Building\Infrastructure\Controllers\DefaultController;

/**
 * Class Routers
 * @package Building\Infrastructure
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