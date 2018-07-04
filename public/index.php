<?php declare(strict_types=1);

namespace Building\App;

use Building\Infrastructure\Kernal;
use Building\Infrastructure\Service\Config;
use Building\Infrastructure\Service\Containers;
use Symfony\Component\HttpFoundation\Request;
use Zend\ServiceManager\ServiceManager;
use Whoops\Run as Whoops;

\call_user_func(function () {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    require_once __DIR__ . '/../vendor/autoload.php';

    /** @var ServiceManager $sm */
    $sm = Containers::init();

    /** @var array $config */
    $config = $sm->get(Config::class);
    $config['app.debug'] ? $sm->get(Whoops::class) : null;

    $request = Request::createFromGlobals();
    $response = Kernal::boot($request);
    $response->send();
});