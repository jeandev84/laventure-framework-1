<?php
namespace Laventure\Foundation\Application;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\ContainerInterface;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;
use Laventure\Foundation\Application\Storage\ServiceStack;


/**
 * @Application
*/
class Application extends Container
{

     /**
      * @var string
     */
     protected $basePath;




     /**
      * Application constructor
      *
      * @param string|null $basePath
     */
     public function __construct(string $basePath = null)
     {
          if ($basePath) {
              $this->basePath($basePath);
          }

          $this->registerBaseBindings();
          $this->registerBaseProviders();
     }



     /**
      * @return string
     */
     public function version(): string
     {
         return '1.0';
     }



     /**
      * @return string
     */
     public function name(): string
     {
         return 'Laventure';
     }




     /**
      * @param string $path
      * @return $this
     */
     public function basePath(string $path): self
     {
         $this->basePath = rtrim($path, '\\/');

         $this->instance('path', $this->basePath);

         $this->registerPaths($this->basePath);

         return $this;
     }




     /**
      * Register application base binding
      *
      * @return void
     */
     protected function registerBaseBindings()
     {
         self::setInstance($this);

         $this->instances([
             Container::class          => $this,
             ContainerInterface::class => $this,
             'app'                     => $this
         ]);
     }





     /**
      * @param string $path
      * @return void
     */
     public function path(string $path = '')
     {
         //todo implements
     }




     /**
      * Register application base services providers
     */
     protected function registerBaseProviders()
     {
         (function () {
             $this->addProviders($this->serviceProviders());
         })();
     }





     /**
      * @return void
     */
     public function bindNamespaces()
     {
         $this->bind('namespaces', $this->getNamespaces());
     }




     /**
      * @return void
     */
     public function bindPaths()
     {
         $this->bind('paths', $this->getPaths());
     }



    /**
     * @param $basePath
     * @return void
     */
    protected function registerPaths($basePath)
    {
        //todo implements
    }





    /**
      * @param Request $request
      * @param Response $response
      * @return void
     */
     public function terminate(Request $request, Response $response)
     {
           // 1. from the request  we have the route we can debug all information


           // 2. show content
           $response->sendContent();



           // 3. we can close connection to database
           // echo "<br>";
           // echo "Application terminated!";
     }




    /**
     * @return string[]
    */
    public function serviceProviders(): array
    {
         return ServiceStack::getProviders();
    }


    /**
     * @return string[]
    */
    protected function getNamespaces(): array
    {
         return [
             'controllers'  => 'App\\Http\\Controller',
             'middlewares'  => 'App\\Http\\Middleware',
             'events'       => 'App\\Event',
             'exceptions'   => 'App\\Exception',
             'entities'     => 'App\\Entity',
             'repositories' => 'App\\Repository',
             'models'       => 'App\\Model',
             'migrations'   => 'App\\Migration',
             'services'     => 'App\\Service',
             'commands'     => 'App\\Console\\Command',
         ];
    }



    /**
     * @return array
    */
    public function getPaths(): array
    {
        return [
            'routes'   => [
                'web'     => 'routes/web.php',
                'api'     => 'routes/api.php',
                'console' => 'routes/console.php',
            ],
            'controllers' => 'app/Http/Controller',
            'models'      => 'app/Model',
            'database' => [
                'app.migrations' => 'app/Migration',
                'laventure.migrations' => 'database/migrations',
                'seeds'      => 'database/seeds'
            ]
        ];
    }

}