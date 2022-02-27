<?php
namespace Laventure\Component\Routing\Utils;


use Laventure\Component\Routing\Exception\RouteCallbackException;
use Laventure\Component\Routing\Route;


/**
 * @RouteCaller
*/
class RouteCaller
{

      /**
        * @param Route $route
        * @return mixed
        * @throws RouteCallbackException
      */
      public function call(Route $route)
      {
          if (! $route->callable()) {

              $controller = $route->getOption('controller');
              $action     = $route->getOption('action');

              if (! class_exists($controller)) {
                  throw new RouteCallbackException(
                      "Controller '{$controller}' does not exist."
                  );
              }

              if (! method_exists($controller, $action)) {
                  throw new RouteCallbackException(
                      "Method '{$action}' does not exist in controller {$controller}"
                  );
              }

              $route->setCallback([new $controller, $action]);
          }

          return $route->call();
      }
}