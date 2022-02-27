<?php
namespace Laventure\Component\Routing;


use Exception;
use Laventure\Component\Routing\Contract\RouteCollectionInterface;



/**
 * @RouteCollection
*/
class RouteCollection implements RouteCollectionInterface
{

    /**
     * Route collection
     *
     * @var Route[]
    */
    protected $routes = [];




    /**
     * List routes by method
     *
     * @var Route[]
     */
    protected $routeList = [];



    /**
     * Named routes collection
     *
     * @var Route[]
    */
    protected $nameList = [];





    /**
     * List of controllers route
     *
     * @var Route[]
    */
    protected $controllerList = [];





    /**
     * @param array $routes
    */
    public function __construct(array $routes = [])
    {
          if ($routes) {
              $this->addRoutes($routes);
          }
    }



    /**
     * @param Route $route
     * @return Route
    */
    public function addRoute(Route $route): Route
    {
        $this->collectRoutes($route);

        $this->refreshNames();

        return $route;
    }



    /**
     * @param string $name
     * @param Route $route
     * @return Route
     */
    public function add(string $name, Route $route): Route
    {
         $route->name($name);

         return $this->addRoute($route);
    }




    /**
     * @param array $routes
     * @return void
    */
    public function addRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }




    /**
     * @param Route $route
     * @return RouteCollection
    */
    public function collectRoutes(Route $route): RouteCollection
    {
        $methods = $route->getMethodsToString();

        $this->routeList[$methods][$route->getPath()] = $route;

        if($controller = $route->getOption('controller')) {
            $this->controllerList[$controller][] = $route;
        }

        $this->routes[] = $route;

        return $this;
    }




    /**
     * @return void
    */
    public function refreshNames()
    {
        $this->nameList = [];

        foreach ($this->routes as $route) {
            if ($name = $route->getName()) {
                if ($this->hasRouteName($name)) {
                    $this->abortIf(
                        new \RuntimeException("Cannot redeclare name '({$name})' for route ({$route->getPath()})")
                    );
                }
                $this->nameList[$name] = $route;
            }
        }
    }




    /**
     * Determine if exist route given name
     *
     * @param string $name
     * @return bool
    */
    public function hasRouteName(string $name): bool
    {
        return array_key_exists($name, $this->nameList);
    }



    /**
     * @param string $name
     * @return Route|null
    */
    public function getNamedRoute(string $name): ?Route
    {
         return $this->nameList[$name] ?? null;
    }


    /**
     * @param Route $route
     * @return void
     */
    public function removeRoute(Route $route)
    {
        $key = array_search($route, $this->routes);
        $method = $route->getMethodsToString();
        $path   = $route->getPath();

        if ($name = $route->getName()) {
            unset($this->nameList[$name]);
        }

        if ($controller = $route->getOption('controller')) {
            unset($this->controllerList[$controller]);
        }

        unset($this->routeList[$method][$path]);
        unset($this->routes[$key]);
    }




    /**
     * @param string $name
     * @return void
    */
    public function remove(string $name)
    {
          if ($route = $this->getNamedRoute($name)) {
               $this->removeRoute($route);
          }
    }



    /**
     * @return array
    */
    public function getRoutes(): array
    {
        return $this->routes;
    }




    /**
     * @return Route[]
    */
    public function getRoutesByMethod(): array
    {
        return $this->routeList;
    }





    /**
     * @return Route[]
    */
    public function getRoutesByName(): array
    {
        return $this->nameList;
    }



    /**
     * @return array
    */
    public function getRoutesByController(): array
    {
         return $this->controllerList;
    }



    /**
     * Throws exception.
     *
     * @param Exception $e
     * @return mixed
    */
    protected function abortIf(Exception $e)
    {
        return (function () use ($e) {
            throw $e;
        })();
    }
}