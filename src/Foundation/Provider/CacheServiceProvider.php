<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Foundation\Service\Cache\Contract\CacheInterface;
use Laventure\Foundation\Service\Cache\FileCache;


/**
 * @CacheServiceProvider
*/
class CacheServiceProvider extends ServiceProvider
{

    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->singleton(CacheInterface::class, function () {
             return new FileCache($this->app['path'].'/storage/cache');
        });
    }
}