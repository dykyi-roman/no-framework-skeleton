<?php

namespace Building\Infrastructure\Service;

use Dotenv\Dotenv;
use Interop\Container\ContainerInterface;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Zend\Expressive\Container\WhoopsFactory;
use Zend\ServiceManager\ServiceManager;
use Whoops\Run as Whoops;

/**
 * Class Containers
 * @package Building\Infrastructure\Service
 */
class Containers
{
    public static function init()
    {
        return new ServiceManager([
            'factories' => [
                Config::class => function (): array {
                    $envConfig = (new Dotenv(__DIR__ . '/../../..'))->load();

                    return Config::parse($envConfig);
                },

                CommandBus::class => function (): CommandBus {
                    $commandBus = new CommandBus();

                    return $commandBus;
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
            ]
        ]);
    }
}