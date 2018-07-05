<?php declare(strict_types=1);

namespace Dykyi\App;

use Dykyi\Infrastructure\Kernal;
use Dykyi\Infrastructure\Service\Config;
use Dykyi\Infrastructure\Service\Containers;
use Symfony\Component\HttpFoundation\Request;
use Whoops\Run as Whoops;

\call_user_func(function () {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    require_once __DIR__ . '/../vendor/autoload.php';

    $sm = Containers::init();

    /** @var array $config */
    $config = $sm->get(Config::class);
    $config['app.debug'] ? $sm->get(Whoops::class) : null;

    $request = Request::createFromGlobals();
    $response = Kernal::handle($request);
    $response->send();
});
