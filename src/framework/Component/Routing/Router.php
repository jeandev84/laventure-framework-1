<?php
namespace Laventure\Component\Routing;


use Closure;
use Laventure\Component\Routing\Common\Resource;
use Laventure\Component\Routing\Contract\RouterInterface;
use Laventure\Component\Routing\Exception\ResourceException;
use Laventure\Component\Routing\Exception\EmptyRoutesException;
use Laventure\Component\Routing\Exception\RouteCallbackException;
use Laventure\Component\Routing\Exception\RouteNotFoundException;
use Laventure\Component\Routing\Resource\ApiResource;
use Laventure\Component\Routing\Resource\WebResource;
use Laventure\Component\Routing\Utils\RouteParamResolver;



/**
 * @Router
 *
 * @package Laventure\Component\Routing
*/
class Router implements RouterInterface
{


    /**
     * Base URL from request
     *
     * @var string
    */
    protected $domain;




    /**
     * @var string
    */
    protected $namespace;




    /**
     * Stock current route
     *
     * @var Route
    */
    protected $route;




    /**
     * @var Route
    */
    protected $defaultRoute;




    /**
     * @var RouteCollection
    */
    protected $routes;




    /**
     * @var RouteParamResolver
    */
    protected $resolver;




    /**
     * @var WebResource[]
    */
    protected $resources = [];



    /**
     * @var ApiResource[]
    */
    protected $apiResources = [];



    /**
     * @var array
    */
    protected $controllerStack = [];



    /**
     * @var string
    */
    protected $controllerPath;



    /**
     * Route defaults patterns
     *
     * @var array
    */
    protected $patterns = [
        'id'    => '\d+', // [0-9]+
        'lang'  => '\w+', // _local
    ];




    
    /**
     * @var array
    */
    protected $groupStack = [
        'prefix'      => '',
        'module'      => '',
        'name'        => '',
        'middlewares' => []
    ];


    
    /**
     * Router constructor
     *
     * @param array $routes
    */
    public function __construct(array $routes = [])
    {
          $this->routes   = new RouteCollection($routes);
          $this->resolver = new RouteParamResolver();
    }


    /**
     * Set base URL
     *
     * @param string $domain
     * @return Router
    */
    public function domain(string $domain): Router
    {
         $this->domain = $domain;

         return $this;
    }



    /**
     * @param string $namespace
     * @return Router
     */
    public function withControllerNamespace(string $namespace): self
    {
         $this->namespace = trim($namespace, '\\');

         return $this;
    }




    /**
     * @return string
    */
    public function getControllerNamespace(): string
    {
        return $this->namespace;
    }




    /**
     * Get option param
     *
     * @param string $name
     * @param null $default
     * @return mixed
    */
    public function getGroupStack(string $name, $default = null)
    {
        return $this->groupStack[$name] ?? $default;
    }




    /**
     * Set global pattern of routes
     *
     * @param string $name
     * @param string $regex
     * @return $this
    */
    public function pattern(string $name, string $regex): self
    {
        $this->patterns[$name] = $regex;

        return $this;
    }




    /**
     * Set global patterns of routes
     *
     * @param array $patterns
     * @return $this
    */
    public function patterns(array $patterns): self
    {
        foreach ($patterns as $name => $regex) {
             $this->pattern($name, $regex);
        }

        return $this;
    }
    




    /**
     * Add options
     *
     * @param array $stack
     * @return $this
    */
    public function addGroupAttributes(array $stack): self
    {
        $this->groupStack = array_merge($this->groupStack, $stack);

        return $this;
    }




    /**
     * @return void
    */
    public function refreshGroupAttributes()
    {
        $this->groupStack = [];
    }




    /**
     * @param string|array $methods
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function map($methods, string $path, $callback, string $name = null): Route
    {
        $optionsParams = [
            'groupPath'   => $this->getGroupStack('prefix'),
            'groupModule' => $this->getGroupStack('module'),
            'groupName'   => $this->getGroupStack('name')
        ];

        $methods      = $this->resolveMethods($methods);
        $path         = $this->resolvePath($path);
        $otherOptions = $this->resolveTargetOptions($callback);
            
        $route = $this->makeRoute($methods, $path, $callback, $optionsParams);

        if ($name) {
            $route->name($name);
        }

        $route->options($otherOptions);

        return $this->routes->addRoute($route);
    }



    /**
     * @param array $methods
     * @param string $path
     * @param $callback
     * @param array $optionsParams
     * @return Route
    */
    public function makeRoute(array $methods, string $path, $callback, array $optionsParams = []): Route
    {
        $route = new Route($methods, $path, $callback, $optionsParams);

        $route->domain($this->domain)
              ->where($this->patterns)
              ->middlewares($this->getGroupStack('middlewares', []));

        return $route;
    }
    
    
    

    /**
     * Add route will be called by method GET
     *
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function get(string $path, $callback, string $name = null): Route
    {
        return $this->map('GET', $path, $callback, $name);
    }





    /**
     * Add route will be called by method POST
     *
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
     */
    public function post(string $path, $callback, string $name = null): Route
    {
        return $this->map('POST', $path, $callback, $name);
    }






    /**
     * Add route will be called by method PUT
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function put(string $path, $callback, string $name = null): Route
    {
        return $this->map('PUT', $path, $callback, $name);
    }




    /**
     * Add route will be called by method PATCH
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function patch(string $path, $callback, string $name = null): Route
    {
        return $this->map('PATCH', $path, $callback, $name);
    }





    /**
     * Add route will be called by method DELETE
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function delete(string $path, $callback, string $name = null): Route
    {
        return $this->map('DELETE', $path, $callback, $name);
    }



    /**
     * Add route will be called by method DELETE
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function options(string $path, $callback, string $name = null): Route
    {
        return $this->map('OPTIONS', $path, $callback, $name);
    }





    /**
     * Add route will be called by method each method [ GET, POST, PUT, DELETE, PATCH ]
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function any(string $path, $callback, string $name = null): Route
    {
        return $this->map('GET|POST|PUT|DELETE|PATCH', $path, $callback, $name);
    }







    /**
     * @param string $prefix
     * @return $this
    */
    public function prefix(string $prefix): self
    {
         return $this->addGroupAttributes(compact('prefix'));
    }






    /**
     * @param string $module
     * @return $this
    */
    public function module(string $module): self
    {
         return $this->addGroupAttributes(compact('module'));
    }



    /**
     * @param string $name
     * @return $this
    */
    public function name(string $name): self
    {
        return $this->setGroupAttribute('name', $name);
    }




    /**
     * @param string|array $middlewares
     * @return $this
    */
    public function middleware($middlewares): self
    {
        return $this->addGroupAttributes(compact('middlewares'));
    }



    /**
     * @param Closure $routes
     * @param array $attributes
     * @return $this
    */
    public function group(Closure $routes, array $attributes = []): self
    {
          if ($attributes) {
              $this->addGroupAttributes($attributes);
          }

          $routes($this);

          $this->refreshGroupAttributes();

          return $this;
    }




    /**
     * @param Closure|null $closure
     * @param array $attributes
     * @return $this
    */
    public function api(Closure $closure = null, array $attributes = []): self
    {
         /*
         if (! $attributes) {
             $attributes = $this->getDefaultApiRouteAttributes();
         }
         */

         $attributes = array_merge($this->getDefaultAttributesAPI(), $attributes);

         if (! $closure) {
             $this->addGroupAttributes($attributes);
             return $this;
         }

         return $this->group($closure, $attributes);
    }




    /**
     * Add route web resource
     *
     * @param string $name
     * @param string $controller
     * @return $this
     */
    public function resource(string $name, string $controller): self
    {
        return $this->addResource(new WebResource($name, $controller));
    }




    /**
     * Add route api resource
     *
     * @param string $name
     * @param string $controller
     * @return $this
    */
    public function resourceAPI(string $name, string $controller): self
    {
         return $this->addResource(new ApiResource($name, $controller));
    }



    /**
     * @param Resource $resource
     * @return $this
    */
    public function addResource(Resource $resource): self
    {
        $name = $resource->getName();
        $resource->setRouteOptions($this->groupStack);
        $resource->map($this);

        if ($resource instanceof ApiResource) {
             $this->apiResources[$name] = $resource;
        }else {
             $this->resources[$name] = $resource;
        }

        return $this;
    }


    /**
     * @param string $name
     * @return bool
    */
    public function hasResource(string $name): bool
    {
         return \array_key_exists($name, $this->resources);
    }



    /**
     * @param string $name
     * @return bool
    */
    public function hasResourceApi(string $name): bool
    {
        return \array_key_exists($name, $this->apiResources);
    }



    /**
     * @return WebResource[]
    */
    public function getResources(): array
    {
        return $this->resources;
    }



    /**
     * @return ApiResource[]
    */
    public function getApiResources(): array
    {
        return $this->apiResources;
    }





    /**
     * @param string $name
     * @return WebResource
     * @throws ResourceException
    */
    public function getResource(string $name): WebResource
    {
         if (! $this->hasResource($name)) {
              throw new ResourceException("Resource class '{$name}' does not exist in resources.");
         }

         return $this->resources[$name];
    }




    /**
     * @param string $name
     * @return ApiResource
     * @throws ResourceException
    */
    public function getApiResource(string $name): ApiResource
    {
         if (! $this->hasResourceApi($name)) {
             throw new ResourceException("Resource class '{$name}' does not exist in api resources.");
         }

         return $this->apiResources[$name];
    }






    /**
     * @return RouteCollection
    */
    public function collection(): RouteCollection
    {
        return $this->routes;
    }




    /**
     * @return Route[]
    */
    public function getRoutes(): array
    {
        return $this->routes->getRoutes();
    }



    /**
     * @inheritDoc
    */
    public function getRoute(): ?Route
    {
        return $this->route;
    }





    /**
     * Set current route
     *
     * @param Route $route
    */
    public function route(Route $route)
    {
          $this->route = $route;
    }



    /**
     * @param Route $route
     * @return void
    */
    public function setDefaultRoute(Route $route)
    {
        $this->defaultRoute = $route;
    }




    /**
     * @return Route
    */
    public function getDefaultRoute(): Route
    {
        return $this->defaultRoute;
    }





    /**
     * @inheritDoc
    */
    public function match(string $requestMethod, string $requestPath)
    {
        foreach ($this->getRoutes() as $route) {
              if ($route->match($requestMethod, $requestPath)) {
                   $this->route($route);
                   return $route;
              }
         }

         return false;
    }


    /**
     * @return mixed
     *
     * @throws RouteNotFoundException
     * @throws EmptyRoutesException
    */
    public function dispatch(string $requestMethod, string $requestPath)
    {
        if (! $this->getRoutes()) {

             if ($this->defaultRoute) {
                 return $this->defaultRoute;
             }

             throw new EmptyRoutesException();
        }

        if (! $route = $this->match($requestMethod, $requestPath)) {
            throw new RouteNotFoundException("route '{$requestPath}' not found!");
        }

        return $route;
    }




    /**
     * @throws RouteCallbackException
    */
    public function dispatchRoute(Route $route)
    {
        return (new RouteDispatcher($route))->dispatch();
    }





    /**
     * Determine if exist route given name
     *
     * @param string $name
     * @return bool
    */
    public function named(string $name): bool
    {
        return $this->routes->hasRouteName($name);
    }





    /**
     * @inheritDoc
    */
    public function generate(string $name, array $parameters = [])
    {
        if (! $route = $this->routes->getNamedRoute($name)) {
             return false;
        }
        
        return sprintf('/%s', $route->convertParams($parameters));
    }




    /**
     * Generate full route path, used for other domain
     *
     * @param string $name
     * @param array $parameters
     * @return string
    */
    public function url(string $name, array $parameters = []): string
    {
        return $this->resolvedDomain() . $this->generate($name, $parameters);
    }




    /**
     * Add option group
     *
     * @param $key
     * @param $value
     * @return $this
    */
    public function setGroupAttribute($key, $value): Router
    {
         $this->groupStack[$key] = $value;

         return $this;
    }




    /**
     * Remove option
     *
     * @param string $key
     * @return void
    */
    public function removeGroupAttribute(string $key)
    {
         unset($this->groupStack[$key]);
    }



    /**
     * @param string $name
     * @return void
    */
    public function remove(string $name)
    {
        $this->routes->remove($name);
    }






    /**
     * @param $callback
     * @return string[]
     */
    protected function resolveTargetOptions($callback): array
    {
        $options = ['controller' => '', 'action' => ''];

        if ($callback instanceof \Closure) {

            $options['action'] = 'Closure';

            return $options;

        } elseif (is_string($callback)) {

            foreach (['@', '::'] as $needle) {

                if (stripos($callback, $needle) !== false) {
                    list($controller, $action) = explode($needle, $callback, 2);
                    $options['controller'] = $this->generateControllerClass($controller);
                    $options['action']     = $action;
                    return $options;
                }
            }

            $options['action'] = $callback;
            return $options;

        }elseif (is_array($callback)) {

            $options['controller'] = $callback[0];
            $options['action']     = $callback[1];

            return $options;

        }

        return $options;
    }





    /**
     * Resolve methods
     *
     * @param $methods
     * @return array
    */
    protected function resolveMethods($methods): array
    {
        return $this->resolver->resolveMethods($methods);
    }




    /**
     * Resolve path
     *
     * @param $path
     * @return string
    */
    protected function resolvePath($path): string
    {
        return $this->resolver->resolvePath($path, $this->getGroupStack('prefix'));
    }



    /**
     * @return string
    */
    protected function resolvedDomain(): string
    {
        return $this->resolver->resolveDomain($this->domain);
    }



    /**
     * @return string
    */
    protected function generateControllerNamespace(): string
    {
        if ($module = $this->getGroupStack('module')) {
            $module = '\\' . trim($module, '\\') . '\\';
        }

        return $this->namespace . $module;
    }




    /**
     * @param string $controllerClass
     * @return string
    */
    public function generateControllerClass(string $controllerClass): string
    {
         return trim($this->generateControllerNamespace(), '\\') . '\\' . $controllerClass;
    }



    /**
     * @param string $controllerPath
     * @return $this
    */
    public function withControllerPath(string $controllerPath): Router
    {
         $this->controllerPath = trim($controllerPath, '\\/');

         return $this;
    }



    /**
     * @return string
    */
    public function getControllerPath(): string
    {
         return $this->controllerPath;
    }



    /**
     * @return string[]
    */
    protected function getDefaultAttributesAPI(): array
    {
        return [
            'prefix' => 'api',
            'module' => 'Api',
            'name'   => 'api.'
        ];
    }

}