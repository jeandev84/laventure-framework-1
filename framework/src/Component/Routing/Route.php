<?php
namespace Laventure\Component\Routing;


/**
 * @see Route
 *
 * @package Laventure\Component\Routing
*/
class Route implements \ArrayAccess
{

    /**
     * route domain or host.
     *
     * @var string
    */
    protected $domain = '';




    /**
     * route path
     *
     * @var string
    */
    protected $path;



    /**
     * route target
     *
     * @var mixed
     */
    protected $target;




    /**
     * route name
     *
     * @var string
    */
    protected $name;




    /**
     * route methods
     *
     * @var array
     */
    protected $methods = [];





    /**
     * route patterns
     *
     * @var array
     */
    protected $patterns = [];




    /**
     * route matches params
     *
     * @var array
     */
    protected $matches = [];




    /**
     * route middlewares
     *
     * @var array
     */
    protected $middlewares = [];




    /**
     * route options
     *
     * @var array
    */
    protected $options = [];




    /**
     * @var mixed
    */
    protected $callback;




    /**
     * Route constructor
     *
     * @param array $methods
     * @param string $path
     * @param mixed $target
     * @param array $options
    */
    public function __construct(array $methods, string $path, $target, array $options = [])
    {
        $this->methods    = $methods;
        $this->path       = $path;
        $this->target     = $target;
        $this->options    = $options;
    }




    /**
     * get route methods
     *
     * @return array
    */
    public function getMethods(): array
    {
        return $this->methods;
    }



    /**
     * @param string $separator
     * @return string
    */
    public function getMethodsToString(string $separator = '|'): string
    {
        return implode($separator, $this->methods);
    }




    /**
     * get route path
     *
     * @return string|null
    */
    public function getPath(): ?string
    {
        return $this->path;
    }




    /**
     * @return string
     */
    public function resolvedPath(): string
    {
        return $this->removeSlashes($this->path);
    }



    /**
     * get route target
     *
     * @return mixed
    */
    public function getTarget()
    {
        return $this->target;
    }




    /**
     * @return array
    */
    public function getValueMatchParams(): array
    {
        return array_values($this->matches);
    }



    /**
     * get route name
     *
     * @return string|null
    */
    public function getName(): ?string
    {
        return $this->name;
    }




    /**
     * get group name
     *
     * @return string|null
    */
    public function getGroupName(): ?string
    {
        return $this->getOption('groupName');
    }




    /**
     * get route patterns
     *
     * @return array
    */
    public function getPatterns(): array
    {
        return $this->patterns;
    }




    /**
     * get matches params
     *
     * @return array
    */
    public function getMatches(): array
    {
        return $this->matches;
    }




    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }





    /**
     * @return array
    */
    public function getOptions(): array
    {
        return $this->options;
    }



    /**
     * @return array
    */
    public function getMiddlewares(): array
    {
         return $this->middlewares;
    }




    /**
     * Add options
     *
     * @param array $options
     * @return Route
    */
    public function options(array $options): Route
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }




    /**
     * @param string $key
     * @param $value
     * @return $this
    */
    public function addOption(string $key, $value): Route
    {
        $this->options[$key] = $value;

        return $this;
    }


    /**
     * Set base URL
     *
     * @param $domain
     * @return Route
    */
    public function domain($domain): Route
    {
         $this->domain = $domain;

         return $this;
    }





    /**
     * @return string
    */
    public function getDomain(): string
    {
        return $this->domain;
    }



    /**
     * @param $path
     * @return string
    */
    protected function preparedPath($path): string
    {
        return '/'. $this->removeSlashes($path);
    }



    /**
     * @param string $groupName
     * @return $this
    */
    public function groupName(string $groupName): Route
    {
        $this->options(compact('groupName'));

        return $this;
    }




    /**
     * @param mixed $callback
     * @return void
    */
    public function setCallback($callback)
    {
         $this->callback = $callback;
    }



    /**
     * @return mixed
    */
    public function getCallback()
    {
        if ($this->callable()) {
            $this->setCallback($this->target);
        }

        return $this->callback;
    }




    /**
     * @return bool
    */
    public function callable(): bool
    {
         return is_callable($this->target);
    }




    /**
     * @return false|mixed
    */
    public function call()
    {
        if (! is_callable($this->getCallback())) {
            return false;
        }

        return call_user_func_array($this->getCallback(), $this->getValueMatchParams());
    }






    /**
     * set route name
     *
     * @param string|null $name
     * @return Route
    */
    public function name(string $name): Route
    {
        $this->name = $this->getGroupName() . $name;

        return $this;
    }




    /**
     * set route middlewares
     *
     * @param $middleware
     * @return $this
    */
    public function middlewares($middleware): Route
    {
        $this->middlewares = array_merge($this->middlewares, (array) $middleware);

        return $this;
    }



    /**
     * set route regex params
     *
     * @param $name
     * @param null $regex
     * @return Route
    */
    public function where($name, $regex = null): Route
    {
        foreach ($this->parseWhere($name, $regex) as $name => $regex) {
            $this->patterns[$name] =  $this->makePattern($name, $regex);
        }

        return $this;
    }




    /**
     * @param string $name
     * @return $this
    */
    public function whereNumber(string $name): Route
    {
        return $this->where($name, '\d+');
    }




    /**
     * @param string $name
     * @return $this
     */
    public function whereText(string $name): Route
    {
        return $this->where($name, '\w+');
    }




    /**
     * @param string $name
     * @return Route
     */
    public function whereAlphaNumeric(string $name): Route
    {
        return $this->where($name, '[^a-z_\-0-9]');
    }



    /**
     * @param string $name
     * @return Route
     */
    public function whereSlug(string $name): Route
    {
        return $this->where($name, '[a-z\-0-9]+');
    }




    /**
     * @param string $name
     * @return Route
     */
    public function anything(string $name): Route
    {
        return $this->where($name, '.*');
    }





    /**
     * @param string|null $requestMethod
     * @return bool
    */
    public function matchMethod(string $requestMethod): bool
    {
        if (\in_array($requestMethod, $this->methods)) {
            $this->options(compact('requestMethod'));
            return true;
        }

        return false;
    }




    /**
     * Determine if the current method and path URL match route
     *
     * @param string $requestMethod
     * @param string $requestPath
     * @return bool
     */
    public function match(string $requestMethod, string $requestPath): bool
    {
         return $this->matchMethod($requestMethod) && $this->matchPath($requestPath);
    }




    /**
     * @param string $requestPath
     * @return false
    */
    public function matchPath(string $requestPath): bool
    {
        if (preg_match($pattern = $this->getPattern(), $this->resolvePath($requestPath), $matches)) {

            $this->matches = $this->filterParams($matches);

            $this->options(compact('pattern', 'requestPath'));

            return true;
        }

        return false;
    }






    /**
     * @return string
    */
    public function getPattern(): string
    {
        $pattern = $this->getPath();

        if ($this->patterns) {
            $pattern = $this->convertPatterns($this->patterns);
        }

        return '#^'. $this->preparedPath($pattern) . '$#i';
    }




    /**
     * Convert path parameters
     *
     * @param array $params
     * @return string
    */
    public function convertParams(array $params): string
    {
        $path = $this->resolvedPath();

        foreach ($params as $k => $v) {
            $path = preg_replace(["/{{$k}}/", "/{{$k}.?}/"], [$v, $v], $path);
        }

        return $path;
    }



    /**
     * @param string $needle
     * @return bool
    */
    public function hasTarget(string $needle): bool
    {
        return stripos($this->target, $needle) !== false;
    }




    /**
     * @param array $matches
     * @return array
    */
    protected function filterParams(array $matches): array
    {
        return array_filter($matches, function ($key) {

            return ! is_numeric($key);

        }, ARRAY_FILTER_USE_KEY);
    }




    /**
     * get path of given URL
     *
     * @param $path
     * @return string
    */
    protected function resolvePath($path): string
    {
        if(stripos($path, '?') !== false) {
            $path = explode('?', $path, 2)[0];
        }

        return $this->preparedPath($path);
    }


    /**
     * @param array $patterns
     * @return string
    */
    protected function convertPatterns(array $patterns): string
    {
        $path = $this->resolvedPath();

        foreach ($patterns as $k => $v) {
            $path = preg_replace(["/{{$k}}/", "/{{$k}.?}/"], [$v, '?'. $v .'?'], $path);
        }

        return $path;
    }



    /**
     * @param string $path
     * @return string
    */
    protected function removeSlashes(string $path): string
    {
        return trim($path, '\\/');
    }



    /**
     * Determine parses
     *
     * @param $name
     * @param $regex
     * @return array
     */
    protected function parseWhere($name, $regex): array
    {
        return \is_array($name) ? $name : [$name => $regex];
    }



    /**
     * @param $regex
     * @return string|string[]
     */
    protected function resolveRegex($regex)
    {
        return str_replace('(', '(?:', $regex);
    }


    /**
     * @param $name
     * @param $regex
     * @return string
     */
    protected function makePattern($name, $regex): string
    {
        return '(?P<'. $name .'>'. $this->resolveRegex($regex) . ')';
    }



    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return property_exists($this, $offset);
    }


    /**
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetGet($offset)
    {
        if(property_exists($this, $offset)) {
            return $this->{$offset};
        }

        return null;
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if(property_exists($this, $offset)) {
            $this->{$offset} = $value;
        }
    }



    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if(property_exists($this, $offset)) {
            unset($this->{$offset});
        }
    }
}