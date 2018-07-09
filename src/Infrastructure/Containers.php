<?php

namespace Dykyi\Infrastructure;

use Dotenv\Dotenv;
use Dykyi\Infrastructure\Service\Config;
use Monolog\Logger;
use Interop\Container\ContainerInterface;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Mustache_Engine;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Zend\ServiceManager\ServiceManager;
use Whoops\Run as Whoops;

/**
 * Class Containers
 * @package Dykyi\Infrastructure\Service
 */
class Containers
{
    /** @var ServiceManager null */
    private $handles;

    public function __construct()
    {
        $this->handles = new ServiceManager([
            'factories' => [
                Config::class => function (): array {
                    $envConfig = (new Dotenv(__DIR__ . '/../../'))->load();

                    return Config::parse($envConfig);
                },

                CommandBus::class => function (): CommandBus {
                    return new CommandBus();
                },

                EventDispatcher::class => function (): EventDispatcher {
                    return new EventDispatcher();
                },

                Whoops::class => function () {
                    $whoops = new \Whoops\Run();
                    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                    $whoops->register();

                    return $whoops;
                },

                Logger::class => function (ContainerInterface $container) {
                    $config = $container->get(Config::class);

                    $logger = new Logger('app');
                    $logger->pushHandler(new StreamHandler(__DIR__ . $config['app.log_path'], Logger::DEBUG));
                    $logger->pushHandler(new FirePHPHandler());

                    return $logger;
                },
                Mustache_Engine::class => function(){
                   $loader = new \Mustache_Loader_FilesystemLoader(
                       dirname(__DIR__) . '/Application/View',
                       ['extension' => '.html',]
                   );

                   $engine = new Mustache_Engine();
                   $engine->setLoader($loader);

                   return $engine;
                },
            ]
        ]);
    }

    public static function init(): Containers
    {
        return new self();
    }

    public function get(string $name)
    {
        if (null === $this->handles){
            $this->handles = new self();
        }

        return $this->handles->get($name);
    }
}