<?php
namespace Laventure\Component\Routing;


use Laventure\Component\Routing\Common\RouteDispatcherAbstract;
use Laventure\Component\Routing\Exception\RouteCallbackException;
use Laventure\Component\Routing\Utils\RouteCaller;


/**
 * @RouteDispatcher
*/
class RouteDispatcher extends RouteDispatcherAbstract
{

    /**
     * @return mixed
     * @throws RouteCallbackException
    */
    public function dispatch()
    {
        return (new RouteCaller())->call($this->route);
    }
}