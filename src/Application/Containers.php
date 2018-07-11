<?php

namespace Dykyi\Application;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;
use Dykyi\Infrastructure\Service\Config;
use Monolog\Logger;
use Interop\Container\ContainerInterface;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Mustache_Engine;
use SimpleBus\Command\Bus\CommandBus;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Zend\ServiceManager\ServiceManager;
use GuzzleHttp\Client as GuzzleClient;
use Whoops\Run as Whoops;
use Stash\Pool as Cache;

/**
 * Class Containers
 *
 * @package Dykyi\Application
 */
class Containers
{
    /**
     *
     * @var ServiceManager null
     */
    private $handles;

    public function __construct()
    {
        $this->handles = new ServiceManager(
            [
            'factories' => [
                Config::class => function (): array {
                    $envConfig = (new Dotenv(__DIR__ . '/../site/'))->load();

                    return Config::parse($envConfig);
                },

                'Guzzle' => function () {
                    return new GuzzleClient();
                },

                'Cache' => function () {
                    return new Cache(new \Stash\Driver\Ephemeral);
                },

                EntityManager::class => function () {
                    $config = Setup::createAnnotationMetadataConfiguration([__DIR__], getenv('app.debug'));
                    $connectionParams = [
                        'dbname' => getenv('bd.dbname'),
                        'user' => getenv('db.user'),
                        'password' => getenv('db.password'),
                        'host' => getenv('db.host'),
                        'driver' => 'pdo_mysql',
                    ];
                    return EntityManager::create($connectionParams, $config);
                },

                MessageBus::class => function (): MessageBus {
                    $bus = new MessageBusSupportingMiddleware();
                    $bus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
                    $commandHandlerMap = new CallableMap(
                        [
                        //                            SomeActionMessage::class => SomeHandler::class,
                        ],
                        new ServiceLocatorAwareCallableResolver(
                            function ($serviceId) {
                                $handler = new $serviceId();
                                //TODO: some logic here
                                return $handler;
                            }
                        )
                    );
                    $commandHandlerResolver = new NameBasedMessageHandlerResolver(
                        new ClassBasedNameResolver(), $commandHandlerMap
                    );
                    $bus->appendMiddleware(new DelegatesToMessageHandlerMiddleware($commandHandlerResolver));
                    return $bus;
                },

                CommandBus::class => function (): MessageBus {
                    $bus = new MessageBusSupportingMiddleware();
                    $bus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
                    $commandHandlerMap = new CallableMap(
                        [
                        //                            SomeActionCommand::class => SomeHandler::class,
                        ],
                        new ServiceLocatorAwareCallableResolver(
                            function ($serviceId) {
                                $handler = new $serviceId();
                                //TODO: some logic here
                                return $handler;
                            }
                        )
                    );
                    $commandHandlerResolver = new NameBasedMessageHandlerResolver(
                        new ClassBasedNameResolver(), $commandHandlerMap
                    );
                    $bus->appendMiddleware(new DelegatesToMessageHandlerMiddleware($commandHandlerResolver));
                    return $bus;
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
                Mustache_Engine::class => function () {
                    $loader = new \Mustache_Loader_FilesystemLoader(
                        dirname(__DIR__) . '/Application/View',
                        ['extension' => '.html',]
                    );

                    $engine = new Mustache_Engine();
                    $engine->setLoader($loader);

                    return $engine;
                },
            ]
            ]
        );
    }

    public static function init(): Containers
    {
        return new self();
    }

    public function get(string $name)
    {
        if (!$this->handles instanceof ServiceManager) {
            $this->handles = new self();
        }

        return $this->handles->get($name);
    }

}