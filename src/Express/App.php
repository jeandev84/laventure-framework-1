<?php
namespace Laventure\Express;

use Closure;
use Exception;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Container\Container;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Routing\Exception\RouteNotFoundException;
use Laventure\Component\Routing\Route;
use Laventure\Component\Routing\Router;
use Laventure\Express\Exception\ResponseException;


/**
 * @App
*/
class App
{

      /**
       * @var Container
      */
      public static $app;




      /**
       * @var App
      */
      protected static $instance;



      /**
       * @var array
      */
      protected $config = [];




      /**
       * @param array $config
      */
      private function __construct(array $config)
      {
           self::$app = new Container();
           $this->config = $config;

           $this->bindServices();
      }



      /**
       * @param array $config
       * @return App|static
      */
      public static function instance(array $config): App
      {
           if (! self::$instance) {
               self::$instance = new static($config);
           }

           return self::$instance;
      }




      /**
       * @return void
      */
      protected function bindServices()
      {
          self::$app->singleton('router', new Router());
          self::$app->singleton('request', Request::createFromGlobals());
      }



      /**
       * @param $methods
       * @param $path
       * @param Closure $closure
       * @return Route
       * @throws Exception
      */
      public function map($methods, $path, Closure $closure): Route
      {
          return $this->route()->map($methods, $path, $closure);
      }


      /**
       * @param $path
       * @param Closure $closure
       * @return Route
       * @throws Exception
      */
      public function get($path, Closure $closure): Route
      {
          return $this->route()->get($path, $closure);
      }




      /**
       * @param $path
       * @param Closure $closure
       * @return Route
       * @throws Exception
      */
      public function post($path, Closure $closure): Route
      {
           return $this->route()->post($path, $closure);
      }



      /**
       * @return Router
       * @throws Exception
      */
      public function route(): Router
      {
           return self::$app->get('router');
      }



      /**
       * @throws Exception
      */
      public function run()
      {
           /** @var Request $request */
           $request = self::$app->get('request');

           if (! $route = $this->route()->match($request->getMethod(), $request->getRequestUri())) {
                throw new RouteNotFoundException();
           }

           $closure = $route->getTarget();
           $params  = $route->getMatches();

           $response = new Response();
           $request->setAttributes($params);

           $response = $closure($request, $response, $params);

           if (! $response instanceof Response) {
                throw new ResponseException("Response handle must be an instance of Response.");
           }

           $response->send();
           $response->sendContent();
      }
}