<?php
namespace Laventure\Foundation\Routing;

use Laventure\Component\Http\Response\JsonResponse;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Routing\Exception\RouteCallbackException;
use Laventure\Component\Routing\Route;
use Laventure\Foundation\Application\Application;
use Laventure\Foundation\Routing\Exception\BadMethodCalledException;


/**
 * @RouteDispatcher
*/
class RouteDispatcher
{

    /**
     * @var Application
    */
    protected $app;



    /**
     * @param Application $app
    */
    public function __construct(Application $app)
    {
         $this->app = $app;
    }



    /**
     * @param array $services
     * @return $this
    */
    public function withServices(array $services): RouteDispatcher
    {
          $this->app->addServices($services);

          return $this;
    }



    /**
     * @param Route $route
     * @return Response
     * @throws \ReflectionException|RouteCallbackException
     * @throws BadMethodCalledException
    */
    public function dispatch(Route $route): Response
    {
        if (! $route->callable()) {

            $controller = $route->getOption('controller');
            $action     = $route->getOption('action');

            if (! class_exists($controller)) {
                throw new RouteCallbackException("Controller '{$controller}' does not exist.");
            }

            if (! method_exists($controller, $action)) {
                throw new BadMethodCalledException("Method '{$action}' does not exist in controller {$controller}");
            }

            return $this->responseProcess(
                $this->app->call($controller, $route->getMatches(), $action)
            );
        }

        return $this->responseProcess(
            $this->app->call($route->getTarget(), $route->getMatches())
        );
    }


    /**
     * @param $response
     * @return JsonResponse|Response
    */
    protected function responseProcess($response = null)
    {
        if ($response instanceof Response) {
            return $response;
        }elseif (is_array($response)) {
            return new JsonResponse($response, 200);
        }

        return new Response($response, 200);
    }
}