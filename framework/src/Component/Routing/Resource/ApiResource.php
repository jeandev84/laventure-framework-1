<?php
namespace Laventure\Component\Routing\Resource;


use Laventure\Component\Routing\Router;



/**
 * @ApiResource
*/
class ApiResource extends WebResource
{


    const ACTIONS = ['list', 'show', 'create', 'edit', 'destroy'];


    /**
      * @param Router $router
      * @return void
    */
    public function map(Router $router)
    {
        $this->make($router, 'GET', '', 'list', 'list')
             ->make($router, 'GET', '/{id}', 'show', 'show')
             ->make($router, 'POST', '', 'create', 'create')
             ->make($router, 'PUT', '/{id}/edit', 'edit', 'edit')
             ->make($router, 'DELETE', '/{id}/destroy', 'destroy', 'destroy');
    }
}