<?php declare(strict_types=1);

namespace Dykyi\App;

use Dotenv\Dotenv;
use Dykyi\Application\Kernal;
use Dykyi\Infrastructure\Containers;
use Symfony\Component\HttpFoundation\Request;
use Whoops\Run as Whoops;

\call_user_func(function () {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    require_once __DIR__ . '/../vendor/autoload.php';

    if (!isset($_SERVER['APP_ENV'])) {
        if (!class_exists(Dotenv::class)) {
            throw new \RuntimeException('APP_ENV environment variable is not defined');
        }
        (new Dotenv(__DIR__.'/../'))->load();
    }

    $env = $_SERVER['APP_ENV'] ?? 'dev';
    $debug = $_SERVER['APP_DEBUG'] ?? ('prod' !== $env);

    if ($debug) {
        Containers::init()->get(Whoops::class);
    }

    $request = Request::createFromGlobals();
    $response = Kernal::handle($request);
    $response->send();
});
