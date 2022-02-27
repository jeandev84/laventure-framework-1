<?php
namespace Laventure\Component\Routing\Common;


use Laventure\Component\Routing\Contract\RouteDispatcherInterface;
use Laventure\Component\Routing\Exception\RouteCallbackException;
use Laventure\Component\Routing\Route;


/**
 * @RouteDispatcherAbstract
*/
abstract class RouteDispatcherAbstract implements RouteDispatcherInterface
{

    /**
     * @var Route
     */
    protected $route;



    /**
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }




    /**
     * @return mixed
     * @throws RouteCallbackException
    */
    abstract public function dispatch();




    /**
     * @return Route
    */
    public function getMatchedRoute(): Route
    {
        return $this->route;
    }
}