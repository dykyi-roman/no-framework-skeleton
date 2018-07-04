<?php

namespace Building\Infrastructure;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Kernal
 * @package Building\Infrastructure
 */
class Kernal
{
    /**
     * @return Response
     */
    public static function boot(Request $request): Response
    {
        $routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
            $routes = Routers::get();
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        };

        $dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return Response::create('404 - Page not found', 404);
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return Response::create('405 - Method not allowed', 405);
            case \FastRoute\Dispatcher::FOUND:
                $className = $routeInfo[1][0];
                $method = $routeInfo[1][1];
                $vars = $routeInfo[2];

                $class = new $className(new Response());
                $response = $class->$method($vars);

                return $response;
        }

        return Response::create('Undefined Error', 500);
    }
}