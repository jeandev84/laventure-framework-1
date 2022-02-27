<?php
namespace Laventure\Component\Routing\Resource;


use Laventure\Component\Routing\Common\Resource;
use Laventure\Component\Routing\Exception\ResourceException;
use Laventure\Component\Routing\Router;



/**
 * @WebResource
*/
class WebResource extends Resource
{


    const ACTIONS = ['list', 'show', 'create', 'edit', 'destroy'];



    /**
     * @param Router $router
     * @return void
    */
    public function map(Router $router)
    {
        $this->make($router, 'GET', 's', 'list', 'list')
             ->make($router, 'GET', '/{id}', 'show', 'show')
             ->make($router, 'GET|POST', '', 'create', 'create')
             ->make($router, 'GET|POST', '/{id}/edit', 'edit', 'edit')
             ->make($router, 'DELETE', '/{id}/destroy', 'destroy', 'destroy');
    }
}