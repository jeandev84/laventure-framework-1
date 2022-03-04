<?php
namespace Laventure\Foundation\Http;



use Exception;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Http\Middleware\Contract\MiddlewareInterface;
use Laventure\Component\Http\Request\Request;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Routing\Exception\EmptyRoutesException;
use Laventure\Component\Routing\Exception\RouteCallbackException;
use Laventure\Component\Routing\Exception\RouteNotFoundException;
use Laventure\Component\Routing\Router;
use Laventure\Component\Templating\Asset;
use Laventure\Component\Templating\Renderer;
use Laventure\Contract\Http\Kernel as HttpKernelContract;
use Laventure\Foundation\Application\Application;
use Laventure\Foundation\Routing\RouteDispatcher;


/**
 * @Kernel
*/
class Kernel implements HttpKernelContract
{

    /**
     * @var Application
    */
    protected $app;




    /**
     * @var Router
    */
    protected $router;




    /**
     * @var array
    */
    protected $middlewarePriority = [];



    /**
     * @var array
    */
    protected $routeMiddlewares = [];





    /**
     * @param Application $app
     * @param Router $router
    */
    public function __construct(Application $app, Router $router)
    {
         $this->app    = $app;
         $this->router = $router;
    }




    /**
     * @param Request $request
     * @return Response
     * @throws Exception
    */
    public function handle(Request $request): Response
    {
        try {

            $response =  $this->dispatchRoute($request);

        } catch (Exception $e) {

            $response = $this->renderException($e);

        }

        /* dump($_ENV, $_SERVER); */

        // events dispatching here!!!

        return $response;
    }




    /**
     * @param Request $request
     * @return Response
     * @throws RouteNotFoundException|RouteCallbackException
     * @throws \ReflectionException
     * @throws Exception
    */
    protected function dispatchRoute(Request $request): Response
    {
         $this->app->instance(Request::class, $request);

         $route =  $this->matchedRoute($request);

         $this->bootMiddlewareHandle($request, $route->getMiddlewares());

         $dispatcher = new RouteDispatcher($this->app);

         $dispatcher->withServices($this->loadServices());

         return $dispatcher->dispatch($route);
    }




    /**
     * @throws EmptyRoutesException
     * @throws RouteNotFoundException
    */
    protected function matchedRoute(Request $request)
    {
       // $path = $request->getUri()->getPath(); // todo review code URI path.

        $route =  $this->router->dispatch($request->getMethod(), $request->getRequestUri());

        $request->setAttributes([
            '_routeName'    => $route->getName(),
            '_routeHandler' => $route->getTarget(),
            '_routeParams'  => $route->getMatches()
        ]);

        $this->app->instance('route', $route);

        return $route;
    }



    /**
     * @param Request $request
     * @param array $routeMiddlewares
     * @return void
     * @throws Exception
    */
    protected function bootMiddlewareHandle(Request $request, array $routeMiddlewares = [])
    {
        $middlewares = array_merge($routeMiddlewares, $this->routeMiddlewares);

        $middleware = $this->app['middleware'];
        $middlewares = $this->resolvedMiddlewares($middlewares);

        $middleware->addMiddlewares($middlewares);

        // todo middleware for setting real host name
        $this->addServiceSettingsWillBeMiddlewaresLater($request);

        // Run Middleware handle
        $middleware->handle($request);
    }




    /**
     * @param array $middlewares
     * @return array
     * @throws Exception
    */
    protected function resolvedMiddlewares(array $middlewares): array
    {
        $resolved = [];

        foreach ($middlewares as $middleware) {
            if (\is_string($middleware)) {
                $middleware = $this->app->get($middleware);
            }

            if ($middleware instanceof MiddlewareInterface) {
                $resolved[] = $middleware;
            }
        }

        return $resolved;
    }



    /**
     * @param Request $request
     * @param Response $response
     * @return void
    */
    public function terminate(Request $request, Response $response)
    {
          $this->app->terminate($request, $response);
    }


    /**
     * @param Exception $e
     * @return Response
     * @throws Exception
    */
    protected function renderException(Exception $e): Response
    {
        /** @var Renderer $view */
        $view = $this->app->get('view');
        $debug   = getenv('APP_DEBUG');
        $envMode = getenv('APP_ENV');

        $fs = $this->app->get(FileSystem::class);

        /* dd($debug); */
        if ($debug) {

            switch ($envMode) {
                 case 'prod':
                        // load prod
                       return $this->displayError($e, 'prod');
                     break;
                 case 'dev':
                        // load dev

                       $this->logErrors(
                           $e->getMessage(),
                           $e->getLine(),
                           $e->getLine(),
                           $fs->locate('storage/temp/log/dev.log')
                       );
                       return $this->displayError($e, 'dev');
                     break;
             }

        } else {

              $template = $e->getCode() . '.php';

              if ($view->exists($template)) {
                    $output =  $view->render($template, compact('e'));
                    return new Response($output, $e->getCode());
              }else {
                   return $this->displayError($e, 'dev');
              }
        }

        return new Response($e->getMessage(), $e->getCode());
    }



    /**
     * Log Errors
     *
     *
     * @param string $message
     * @param string $file
     * @param string $line
     */
    public function logErrors($message, $file, $line, $destination = '')
    {
        error_log(
            "[". date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$file}\n=============\n",
            3,
             $destination
        );
    }





    /**
     * @param Exception $e
     * @param string $file
     * @return Response
     * @throws Exception
    */
    protected function displayError(Exception $e, string $file): Response
    {
         $view = $this->app->get('view');
         $view->withResource(__DIR__ . '/Resources/');
         $output = $view->render(sprintf('errors/%s.php', $file), compact('e'));
         return new Response($output, $e->getCode());
    }




    /**
     * @param Request $request
     * @return void
    */
    protected function addServiceSettingsWillBeMiddlewaresLater(Request $request)
    {
         // todo refactoring remove all logic somewhere for cleanup Kernel
         /** @var Asset $asset */
         $asset = $this->app[Asset::class];
         $asset->baseUrl($request->getBaseURL());
    }




    /**
     * @return array
    */
    protected function loadServices(): array
    {
        // todo refactoring remove all logic somewhere for cleanup Kernel

         /** @var FileSystem $fs */
         $fs = $this->app->get('fileSystem');
         $serviceNames = $fs->loadResourceFileNames('app/Service/*.php');

         $services = [];

         foreach ($serviceNames as $serviceName) {
             $services[] = "App\\Service\\$serviceName";
         }

         return $services;
    }
}