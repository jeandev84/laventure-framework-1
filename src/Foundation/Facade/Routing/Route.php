<?php
namespace Laventure\Foundation\Facade\Routing;


use Laventure\Component\Container\Facade\Facade;
use Laventure\Component\Routing\Router;




/**
 * @Route
*/
class Route extends Facade
{
     protected static function getFacadeAccessor(): string
     {
          return Router::class;
     }
}