<?php
namespace Laventure\Component\Routing\Contract;


use Laventure\Component\Routing\Route;


/**
 * @RouteCollectionInterface
*/
interface RouteCollectionInterface
{

    /**
     * Add route
     *
     * @param Route $route
     * @return mixed
    */
    public function addRoute(Route $route);




    /**
     * Get route collection
     *
     * @return array
    */
    public function getRoutes(): array;

}