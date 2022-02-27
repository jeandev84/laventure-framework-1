<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Routing\Router;
use Laventure\Component\Routing\UrlGenerator;


/**
 * @UrlGeneratorServiceProvider
*/
class UrlGeneratorServiceProvider extends ServiceProvider
{

    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->singleton(UrlGenerator::class, function (Router $router) {

             // todo check the real base url from container and inject here !!!
             return new UrlGenerator($router, 'http://localhost:8080');
        });
    }
}