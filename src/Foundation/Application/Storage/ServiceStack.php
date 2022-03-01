<?php
namespace Laventure\Foundation\Application\Storage;


use Laventure\Foundation\Provider\ApplicationServiceProvider;
use Laventure\Foundation\Provider\AssetServiceProvider;
use Laventure\Foundation\Provider\CacheServiceProvider;
use Laventure\Foundation\Provider\ConfigurationServiceProvider;
use Laventure\Foundation\Provider\ConsoleServiceProvider;
use Laventure\Foundation\Provider\DatabaseServiceProvider;
use Laventure\Foundation\Provider\EventDispatcherServiceProvider;
use Laventure\Foundation\Provider\FileSystemServiceProvider;
use Laventure\Foundation\Provider\MiddlewareServiceProvider;
use Laventure\Foundation\Provider\RoutingServiceProvider;
use Laventure\Foundation\Provider\UrlGeneratorServiceProvider;
use Laventure\Foundation\Provider\ViewServiceProvider;

/**
 * @ServiceStack
*/
class ServiceStack
{


    /**
     * @return string[]
    */
    public static function getProviders(): array
    {
        return [
            ApplicationServiceProvider::class,
            FileSystemServiceProvider::class,
            ConfigurationServiceProvider::class,
            CacheServiceProvider::class,
            DatabaseServiceProvider::class,
            MiddlewareServiceProvider::class,
            RoutingServiceProvider::class,
            AssetServiceProvider::class,
            EventDispatcherServiceProvider::class,
            CacheServiceProvider::class,
            UrlGeneratorServiceProvider::class,
            ViewServiceProvider::class,
            ConsoleServiceProvider::class
        ];
    }
}