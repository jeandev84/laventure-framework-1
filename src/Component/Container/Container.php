<?php
namespace Laventure\Component\Container;


use ArrayAccess;
use Closure;
use Exception;
use InvalidArgumentException;
use Laventure\Component\Container\Exception\ContainerException;
use Laventure\Component\Container\Facade\Facade;
use Laventure\Component\Container\ServiceProvider\Contract\BootableServiceProvider;
use Laventure\Component\Container\ServiceProvider\Exception\ServiceProviderException;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;



/**
 * @Container
 *
 * @package Laventure\Component\Container
*/
class Container implements ContainerInterface, ArrayAccess
{

    /**
     * @var Container
     */
    protected static $instance;



    /**
     * storage all bound params
     *
     * @var array
     */
    protected $bindings = [];



    /**
     * storage all instances
     *
     * @var array
     */
    protected $instances = [];


    /**
     * storage all resolved params
     *
     * @var array
     */
    protected $resolved  = [];



    /**
     * storage all aliases
     *
     * @var array
     */
    protected $aliases = [];




    /**
     * collection service providers
     *
     * @var array
     */
    protected $providers = [];





    /**
     * collection facades
     *
     * @var array
    */
    protected $facades = [];



    /**
     * @var array
    */
    protected $services = [];



    /**
     * Set container instance
     *
     * @param ContainerInterface|null $instance
    */
    public static function setInstance(ContainerInterface $instance = null): ?ContainerInterface
    {
        return static::$instance = $instance;
    }




    /**
     * Get container instance <Singleton>
     *
     * @return Container|static
    */
    public static function getInstance(): Container
    {
        if(is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }



    /**
     * Get bindings params
     *
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }


    /**
     * Get all instances
     *
     * @return array
     */
    public function getInstances(): array
    {
        return $this->instances;
    }


    /**
     * Get resolved params
     *
     * @return array
     */
    public function getResolved(): array
    {
        return $this->resolved;
    }



    /**
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }


    /**
     * @param string $abstract
     * @return mixed
     */
    public function getConcreteContext(string $abstract)
    {
        if(! $this->hasConcrete($abstract)) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
    }



    /**
     * @param string $abstract
     * @param null $concrete
     * @param bool $shared
     * @return $this
     */
    public function bind(string $abstract, $concrete = null, bool $shared = false): Container
    {
        if(\is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');

        return $this;
    }



    /**
     * Bind many params in the container
     *
     * @param array $bindings
     */
    public function binds(array $bindings)
    {
        foreach ($bindings as $bind) {
            list($abstract, $concrete, $shared) = $bind;
            $this->bind($abstract, $concrete, $shared);
        }
    }



    /**
     * Determine if the given param is bound
     *
     * @param $abstract
     * @return bool
     */
    public function bound($abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }



    /**
     * @param array $services
     * @return void
    */
    public function addServices(array $services)
    {
         foreach ($services as $service) {
             $this->addService($service);
         }
    }




    /**
     * @param string|object $service
     * @return $this
    */
    public function addService($service): self
    {
        $service = $this->resolveGivenConcrete($service);

        if (\is_object($service)) {
            $name = $this->getClassName($service);
            $this->services[$name] = $service;
            $this->instance($name, $service);
        }

        return $this;
    }




    /**
     * @param string $name
     * @return void
    */
    public function removeService(string $name)
    {
          unset($this->services[$name]);
    }



    /**
     * @param $object
     * @return string
    */
    protected function getClassName($object): string
    {
         return (new \ReflectionObject($object))->getShortName();
    }


    /**
     * @param string $abstract
     * @param $concrete
     * @return $this|Container
     */
    public function singleton(string $abstract, $concrete): Container
    {
        return $this->bind($abstract, $concrete, true);
    }




    /**
     * @param $abstract
     * @return bool
     */
    public function shared($abstract): bool
    {
        return $this->hasInstance($abstract) || $this->onlyShared($abstract);
    }



    /**
     * Share a parameter
     *
     * @param $abstract
     * @param $concrete
     * @return mixed
     */
    public function share($abstract, $concrete)
    {
        if(! $this->hasInstance($abstract)) {
            $this->instances[$abstract] = $concrete;
        }

        return $this->instances[$abstract];
    }




    /**
     * Set instance
     *
     * @param $abstract
     * @param $concrete
     * @return Container
     */
    public function instance($abstract, $concrete): Container
    {
        $this->instances[$abstract] = $concrete;

        return $this;
    }


    /**
     * @param array $instances
     * @return $this
    */
    public function instances(array $instances): Container
    {
         $this->instances = array_merge($this->instances, $instances);

         return $this;
    }




    /**
     * @param string $abstract
     * @return mixed
     * @throws Exception
     */
    public function factory(string $abstract)
    {
        return $this->make($abstract);
    }




    /**
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     * @throws Exception
    */
    public function make(string $abstract, array $parameters = [])
    {
        return $this->resolve($abstract, $parameters);
    }


    /**
     * @param $abstract
     * @param $alias
     * @return Container
    */
    public function alias($abstract, $alias): Container
    {
        $this->aliases[$abstract] = $alias;

        return $this;
    }




    /**
     * @param $abstract
     * @return mixed
     */
    public function getAlias($abstract)
    {
        if($this->hasAlias($abstract)) {
            return $this->aliases[$abstract];
        }

        return $abstract;
    }



    /**
     * @param $id
     * @return bool
     */
    public function has($id): bool
    {
        return $this->bound($id) || $this->hasInstance($id) || $this->hasAlias($id);
    }



    /**
     * @param $id
     * @return bool
     */
    public function hasInstance($id): bool
    {
        return isset($this->instances[$id]);
    }



    /**
     * @param $id
     * @return bool
     */
    public function hasAlias($id): bool
    {
        return isset($this->aliases[$id]);
    }



    /**
     * @param $id
     * @return bool
     */
    public function resolved($id): bool
    {
        return isset($this->resolved[$id]);
    }



    /**
     * @param $abstract
     * @return bool
     */
    protected function hasConcrete($abstract): bool
    {
        return isset($this->bindings[$abstract])
               && isset($this->bindings[$abstract]['concrete']);
    }



    /**
     * @param $id
     * @return mixed|null
    */
    public function get($id)
    {
        return (function () use ($id) {

            try {
                return $this->resolve($id);
            } catch (Exception $e) {

                if ($this->has($id)) {
                    throw $e;
                }

                throw new ContainerException($e->getMessage(), $e->getCode());
            }

        })();
    }


    /**
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function resolve(string $abstract, array $parameters = [])
    {
        // get abstract from alias
        $abstract = $this->getAlias($abstract);


        // get concrete context
        $concrete = $this->getConcreteContext($abstract);

        if($this->resolvable($concrete)) {
            $concrete = $this->resolveConcrete($concrete, $parameters);
            $this->resolved[$abstract] = true;
        }

        if($this->shared($abstract)) {
            return $this->share($abstract, $concrete);
        }

        return $concrete;
    }



    /**
     * get function dependencies
     *
     * @param array $dependencies
     * @param array $params
     * @return array
     * @throws Exception
    */
    public function resolveDependencies(array $dependencies, array $params = []): array
    {
        $resolvedDependencies = [];

        foreach ($dependencies as $parameter) {

            $dependency = $parameter->getClass();

            if($parameter->isOptional()) { continue; }
            if($parameter->isArray()) { continue; }

            if(\is_null($dependency)) {
                $resolvedDependencies[] = $this->resolveParameters($parameter, $params);
            } else {
                $resolvedDependencies[] = $this->get($dependency->getName());
            }
        }

        return $resolvedDependencies;
    }




    /**
     * @param ReflectionParameter $parameter
     * @param array $params
     * @return mixed
    */
    protected function resolveParameters(ReflectionParameter $parameter, array $params)
    {
          if ($parameter->isDefaultValueAvailable()) {
              return $parameter->getDefaultValue();
          }else {
              if (\array_key_exists($parameter->getName(), $params)) {
                  return $params[$parameter->getName()];
              } elseif($this->hasInstance($parameter->getName())) {
                  return $parameter->getName();
              }else {
                  $this->unresolvableDependencyException($parameter);
              }
          }
    }



    /**
     * @param ReflectionParameter $parameter
     * @return mixed
    */
    protected function unresolvableDependencyException(ReflectionParameter $parameter)
    {
        /* $name = $parameter->getName(); */
        $message = "Unresolvable dependency [{$parameter}] in class {$parameter->getDeclaringClass()->getName()}";

        throw new InvalidArgumentException($message);
    }



    /**
     * @param $concrete
     * @return bool
    */
    public function resolvable($concrete): bool
    {
        if($concrete instanceof Closure) {
            return true;
        }

        if (\is_string($concrete) && \class_exists($concrete)) {
            return true;
        }

        return false;
    }


    /**
     * @param $concrete
     * @param array $params
     * @return mixed
     * @throws Exception
    */
    public function resolveConcrete($concrete, array $params = [])
    {
        if($concrete instanceof Closure) {
            return $this->call($concrete, $params);
        }

        return $this->makeInstance($concrete, $params);
    }


    
    /**
     * @param string $concrete
     * @param array $params
     * @return object|null
     * @throws ReflectionException
     * @throws Exception
    */
    protected function makeInstance(string $concrete, array $params = []): ?object
    {
        $reflectedClass = new ReflectionClass($concrete);

        if($reflectedClass->isInstantiable()) {

            $constructor = $reflectedClass->getConstructor();

            if(\is_null($constructor)) {
                return $reflectedClass->newInstance();
            }

            $dependencies = $this->resolveDependencies($constructor->getParameters(), $params);
            
            return $reflectedClass->newInstanceArgs($dependencies);
        }

        throw new ReflectionException('Cannot instantiate given argument ('. $concrete .')');
    }



    /**
     * @param $concrete
     * @param array $params
     * @param string $method
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     */
    public function call($concrete, array $params = [], string $method = '')
    {
        if (is_callable($concrete)) {
            if ($concrete instanceof Closure) {
                return $this->callAnonymous($concrete, $params);
            }elseif (is_string($concrete)) {
                return call_user_func($concrete);
            }
        }elseif (\is_string($concrete) && $method) {
            return $this->callAction($concrete, $method, $params);
        }else{
            throw new InvalidArgumentException("argument {$concrete} is not callable");
        }
    }




    /**
     * @throws ReflectionException
     * @throws Exception
    */
    public function callAnonymous(Closure $concrete, array $params = [])
    {
        $reflectedFunction  = new \ReflectionFunction($concrete);
        $functionParameters = $reflectedFunction->getParameters();
        $params = $this->resolveDependencies($functionParameters, $params);

        return call_user_func($concrete, ...$params);
    }




    /**
     * @param string $concrete
     * @param string $method
     * @param array $params
     * @return false|mixed
     * @throws ReflectionException
     * @throws Exception
    */
    public function callAction(string $concrete, string $method, array $params = [])
    {
        $reflectedMethod = new \ReflectionMethod($concrete, $method);
        $arguments = $this->resolveDependencies($reflectedMethod->getParameters(), $params);

        $object = $this->get($concrete);

        $implements = class_implements($object);

        if (isset($implements[ContainerAwareInterface::class])) {
            $object->setContainer($this);
        }

        return call_user_func_array([$object, $method], $arguments);
    }




    /**
     * @param $object
     * @param $method
     * @param array $params
     * @return false|mixed
     */
    public function callback($object, $method, array $params = [])
    {
        return call_user_func_array([$object, $method], $params);
    }



    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        $this->aliases = [];
        $this->resolved = [];
        $this->bindings = [];
        $this->instances = [];
    }


    /**
     * @param $abstract
     * @return bool
     */
    protected function onlyShared($abstract): bool
    {
        return isset($this->bindings[$abstract]['shared'])
               && $this->bindings[$abstract]['shared'] === true;
    }


    /**
     * add service providers
     *
     * @param array $providers
     * @return Container
     * @throws ServiceProviderException|Exception
     */
    public function addProviders(array $providers): Container
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }

        return $this;
    }




    /**
     * add service provider
     *
     * @param ServiceProvider|string $provider
     * @return $this
     * @throws ServiceProviderException
     * @throws Exception
     */
    public function addProvider($provider): Container
    {
        $provider = $this->resolveProvider($provider);

        return $this->providerProcess($provider);
    }



    /**
     * @param $provider
     * @return ServiceProvider
     * @throws ServiceProviderException
     * @throws Exception
     */
    protected function resolveProvider($provider): ServiceProvider
    {
        // todo refactoring

        $provider = $this->resolveGivenConcrete($provider);

        if (! $provider instanceof ServiceProvider) {

            $msg = "cannot resolve abstract service provider.";

            if (is_string($provider)) {
                $msg = "cannot resolve {$provider} service provider";
            }

            throw new ServiceProviderException($msg);
        }

        return $provider;
    }


    
    
    /**
     * @param $concrete
     * @return false|mixed|object|string|null
    */
    protected function resolveGivenConcrete($concrete)
    {
         return \is_string($concrete) ? $this->get($concrete) : $concrete;
    }




    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->providers;
    }




    /**
     * @param ServiceProvider $provider
     * @return Container
     */
    protected function providerProcess(ServiceProvider $provider): Container
    {
        $provider->setContainer($this);

        if (! \in_array($provider, $this->providers)) {

            $implements = class_implements($provider);
            if (isset($implements[BootableServiceProvider::class])) {
                if (method_exists($provider, 'boot')) {
                    $provider->boot();
                }
            }
            $provider->register();
            $this->providers[] = $provider;

        }

        return $this;
    }




    /**
     * @param Facade|string $facade
     * @return $this
     * @throws Exception
     */
    public function addFacade($facade): Container
    {
        $facade = $this->validateFacade($facade);

        if (! \in_array($facade, $this->facades)) {

            $facade->setContainer($this);

            $this->facades[] = $facade;

        }


        return $this;
    }





    /**
     * @param $facade
     * @return Facade
     * @throws Exception
     */
    protected function validateFacade($facade): Facade
    {
        $facade = $this->resolveGivenConcrete($facade);

        if (! $facade instanceof Facade) {
            throw new InvalidArgumentException('cannot resolve given argument for adding facade.');
        }

        return $facade;
    }



    /**
     * @param array $facades
     * @throws Exception
     */
    public function addFacades(array $facades)
    {
        foreach ($facades as $facade) {
            $this->addFacade($this->get($facade));
        }
    }



    /**
     * @return array
     */
    public function getFacades(): array
    {
        return $this->facades;
    }





    /**
     * @param mixed $id
     * @return bool
     */
    public function offsetExists($id): bool
    {
        return $this->has($id);
    }


    /**
     * @param mixed $id
     * @return mixed
     * @throws Exception
    */
    public function offsetGet($id)
    {
        return $this->get($id);
    }


    /**
     * @param mixed $id
     * @param mixed $value
     */
    public function offsetSet($id, $value)
    {
        $this->bind($id, $value);
    }


    /**
     * @param mixed $id
    */
    public function offsetUnset($id)
    {
        unset(
            $this->bindings[$id],
            $this->instances[$id],
            $this->resolved[$id]
        );
    }


    /**
     * @param $name
     * @return array|bool|mixed|object|string|null
    */
    public function __get($name)
    {
        return $this[$name];
    }




    /**
     * @param $name
     * @param $value
    */
    public function __set($name, $value)
    {
        $this[$name] = $value;
    }
}