<?php
namespace Laventure\Component\Routing\Common;


use Laventure\Component\Routing\Route;
use Laventure\Component\Routing\Router;



/**
 * @Resource
*/
abstract class Resource
{


    /**
     * @var string
    */
    protected $name;





     /**
      * @var array
     */
     protected $routes = [];



     /**
      * @var string
     */
     protected $controller;



     /**
      * @var array
     */
     protected $routeOptions = [];




     /**
      * @var array
     */
     protected $params = [];




     /**
      * @var array
     */
     protected $items = [];




    /**
     * @param string $name
     * @param string $controller
     * @param array $routeOptions
    */
    public function __construct(string $name, string $controller, array $routeOptions = [])
    {
         $this->name         = $name;
         $this->controller   = $controller;

         if ($routeOptions) {
            $this->setRouteOptions($routeOptions);
         }
    }




    /**
     * @return string
    */
    public function getName(): string
    {
        return $this->name;
    }





    /**
     * @param array $routeOptions
     * @return void
    */
    public function setRouteOptions(array $routeOptions)
    {
        $this->routeOptions = $routeOptions;
    }






    /**
     * @param string $suffix
     * @return string
    */
    protected function path(string $suffix = ''): string
    {
        $path =  trim($this->name, 's') . $suffix;

        $this->param('paths', $path);

        return $path;
    }



    /**
     * @param string $suffix
     * @return string
    */
    protected function action(string $suffix): string
    {
        $action = sprintf('%s@%s', $this->controller, $suffix);

        $this->param('actions', $action);

        return $action;
    }




    /**
     * @param string $suffix
     * @return string
    */
    protected function name(string $suffix): string
    {
        $name = $this->name . '.'. $suffix;

        $this->param('names', $name);

        return $name;
    }



    /**
     * @param string $name
     * @param $value
     * @return void
    */
    protected function param(string $name, $value)
    {
        $this->params[$name][] = $value;
    }





    /**
     * @return mixed
    */
    public function getActions()
    {
        return $this->params['actions'];
    }




    /**
     * @return string
    */
    public function getControllerClass(): string
    {
        return $this->controller;
    }




    /**
     * @return array
    */
    public function getParams(): array
    {
        return $this->params;
    }




    /**
     * @param Router $router
     * @return mixed
    */
    abstract public function map(Router $router);



    /**
     * @param Route $route
     * @return void
    */
    public function addRoute(Route $route)
    {
         $this->routes[] = $route;
    }




    /**
     * @param array $items
     * @return void
    */
    public function addRouteItems(array $items)
    {
         $this->items[$this->controller][] = $items;
    }




    /**
     * @return array
    */
    public function getItems(): array
    {
        return $this->items;
    }




    /**
     * @param Router $router
     * @param string $methods
     * @param string $path
     * @param string $action
     * @param string $name
     * @return $this
    */
    protected function make(Router $router, string $methods, string $path, string $action, string $name): self
    {
         $route = $router->map($methods, $path = $this->path($path), $this->action($action), $name = $this->name($name));

         $this->addRoute($route);

         $this->addRouteItems(
             compact('methods', 'path', 'action', 'name')
         );

         return $this;
    }




    /**
     * @return Route[]
    */
    function getRoutes(): array
    {
         return $this->routes;
    }
}