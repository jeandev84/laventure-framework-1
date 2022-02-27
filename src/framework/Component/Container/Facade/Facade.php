<?php
namespace Laventure\Component\Container\Facade;

use Exception;
use Laventure\Component\Container\ContainerInterface;


/**
 * Class Facade
 *
 * @package Laventure\Component\Container\Facade
*/
abstract class Facade
{

    /**
     * @var ContainerInterface
    */
    protected static $container;


    /**
     * @var mixed
     */
    protected static $resolved;


    /**
     * Set container
     * @param ContainerInterface $container
   */
    public function setContainer(ContainerInterface $container)
    {
        static::$container = $container;
    }




    /**
     * Get instance of Facade
     *
     * dump($accessor, static::$container)
     * @throws Exception
     */
    public static function getFacadeInstance()
    {
        $accessor = static::getFacadeAccessor();

        if($resolved = static::$resolved[$accessor] ?? null) {
            return $resolved;
        }

        return static::$resolved[$accessor] = static::$container->get($accessor);
    }



    /**
     * @param $method
     * @param $arguments
     * @return bool
     * @throws Exception
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeInstance();

        if(! method_exists($instance, $method)) {
            return false;
        }

        return call_user_func_array([$instance, $method], $arguments);
    }


    /**
     * Get name of facade to be resolve in container
     * @throws Exception
    */
    protected static function getFacadeAccessor(): string
    {
        throw new Exception('unable facade name for ('. get_called_class() .')');
    }
}