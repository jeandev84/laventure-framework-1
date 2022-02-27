<?php
namespace Laventure\Foundation\Provider;

use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Http\Middleware\Middleware;


/**
 * @MiddlewareServiceProvider
*/
class MiddlewareServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('middleware', function () {
            return new Middleware();
        });
    }
}