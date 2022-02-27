<?php
namespace Laventure\Component\Http\Middleware;


use Laventure\Component\Http\Middleware\Contract\MiddlewareInterface;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;


/**
 * @Middleware
*/
class Middleware
{

    /**
     * @var Closure
    */
    protected $start;



    /**
     * @var array
    */
    protected $middlewares = [];



    /**
     * MiddlewareStack constructor.
     */
    public function __construct()
    {
        $this->start = function (Request $request) {
            return '';
        };
    }



    /**
     * @param MiddlewareInterface $middleware
     * @return Middleware
    */
    public function add(MiddlewareInterface $middleware): Middleware
    {
        $next = $this->start;

        $response = new Response();

        $this->start = function (Request $request) use ($middleware, $next, $response) {
            $next = $middleware->handle($request, $next);
            $middleware->terminate($request, $response);
            return $next;
        };

        $this->middlewares[] = $middleware;

        return $this;
    }




    /**
     * @param array $middlewares
     * @return Middleware
    */
    public function addMiddlewares(array $middlewares): Middleware
    {
        foreach ($middlewares as $middleware) {
            $this->add($middleware);
        }

        return $this;
    }



    /**
     * Run all middlewares
     * @param Request $request
     * @return mixed
    */
    public function handle(Request $request)
    {
        return call_user_func($this->start, $request);
    }




    /**
     * @return array
    */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}