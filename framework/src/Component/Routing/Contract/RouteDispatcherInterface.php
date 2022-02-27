<?php
namespace Laventure\Component\Routing\Contract;


/**
 * @RouteDispatcherInterface
*/
interface RouteDispatcherInterface
{


    /**
     * Get matched route
     *
     * @return mixed
    */
    public function getMatchedRoute();




    /**
     * Dispatch route used for simple application
     * if we don't need to parse dependencies classes to the controller or method.
     *
     * @return mixed
    */
    public function dispatch();
}