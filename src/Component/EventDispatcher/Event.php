<?php
namespace Laventure\Component\EventDispatcher;



use ReflectionClass;

/**
 * @Event
*/
abstract class Event
{
    /**
     * @return string
    */
    public function getName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}