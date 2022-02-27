<?php
namespace Laventure\Foundation\Provider;


use Exception;
use Laventure\Component\Container\Container;
use Laventure\Component\Container\ServiceProvider\Contract\BootableServiceProvider;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Debug\Exception\ErrorHandler;
use Laventure\Component\Dotenv\Dotenv;
use Laventure\Foundation\Application\Application;
use Laventure\Foundation\Server\ServerLocalReader;


/**
 * @ApplicationServiceProvider
*/
class ApplicationServiceProvider extends ServiceProvider implements BootableServiceProvider
{


    /**
     * @inheritDoc
     * @throws Exception
    */
    public function boot()
    {
        $this->loadWhoops();
        $this->loadEnvironments();
        $this->loadHelpers();
        $this->loadClassAliases();
        $this->loadFacades();
    }



    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->singleton(Application::class, function () {
            return Container::getInstance();
        });
    }


    /**
     * @return void
     * @throws Exception
    */
    protected function loadEnvironments()
    {
        Dotenv::create($this->app['path'])->load();

        $this->app->bind('app.env', getenv('APP_ENV'));
        $this->app->bind('app.debug', getenv('APP_DEBUG'));
    }



    /**
     * @return void
    */
    protected function loadHelpers()
    {
        require __DIR__ . '/../helpers.php';
    }



    /**
     * @return void
    */
    protected function loadClassAliases()
    {
        foreach ($this->getClassAliases() as $alias => $className) {
            \class_alias($className, $alias);
        }
    }



    /**
     * @return void
     * @throws Exception
    */
    protected function loadFacades()
    {
        $this->app->addFacades($this->getFacadeStack());
    }




    /**
     * @return string[]
     */
    protected function getClassAliases(): array
    {
        return [
            'Route' => "Laventure\Foundation\Facade\Routing\Route",
            'Asset' => "Laventure\Foundation\Facade\Templating\Asset",
        ];
    }



    /**
     * @return string[]
    */
    protected function getFacadeStack(): array
    {
        return [
            "Laventure\Foundation\Facade\Routing\Route",
            "Laventure\Foundation\Facade\Templating\Asset",
            "Laventure\Foundation\Facade\Database\DB",
            "Laventure\Foundation\Facade\Database\Schema",
        ];
    }


    protected function loadWhoops()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }



    /**
     * @return void
    */
    protected function loadLaventureExceptionHandler()
    {
         $laventureException = new ErrorHandler();
    }
}