<?php
namespace Laventure\Foundation\Provider;

use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Routing\Router;
use Laventure\Component\Routing\Route;
use ReflectionException;


/**
 * @RoutingServiceProvider
*/
class RoutingServiceProvider extends ServiceProvider
{

    /**
     * @return void
     * @throws ReflectionException
    */
    public function register()
    {
         $this->registerRouter();
         $this->loadRoutes();
         $this->bindRoutes();
    }




    /**
     * @return void
    */
    protected function registerRouter()
    {
        $this->app->singleton(Router::class, function () {
            $router = new Router();
            $route = $this->makeDefaultRoute($router);

            /* $router->domain('http://localhost:8080') */

            $router->namespace('App\\Http\\Controller')
                   ->withControllerPath('app/Http/Controller')
                   ->setDefaultRoute($route);

            return $router;
        });
    }





    /**
     * @param Router $router
     * @return Route
    */
    protected function makeDefaultRoute(Router $router): Route
    {
        $callback = $this->getDefaultController();
        $options  = explode('@', $callback, 2);

       return $router->makeRoute(['GET'], '/', $callback, [
            'controller' => $options[0],
            'action'     => $options[1]
        ])->name('route.default');
    }





    /**
     * @return string
    */
    private function getDefaultController(): string
    {
        return 'Laventure\\Foundation\\Routing\\DefaultController@index';
    }



    /**
     * @return void
     * @throws ReflectionException
    */
    private function loadRoutes()
    {
        $this->app->call(function (FileSystem $fs) {
            $fs->load('/routes/api.php');
            $fs->load('/routes/web.php');
        });
    }




    /**
     * @return void
    */
    private function bindRoutes()
    {
        $this->app->bind('_routes',  function (Router $router) {
            return $router->getRoutes();
        });
    }
}