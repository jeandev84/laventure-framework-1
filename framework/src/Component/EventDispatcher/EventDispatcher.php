<?php
namespace Laventure\Component\EventDispatcher;

use Laventure\Component\EventDispatcher\Contract\EventDispatcherInterface;
use ReflectionClass;

/**
 * @EventDispatcher
*/
class EventDispatcher implements EventDispatcherInterface
{

    /**
     * @var EventListener[]
    */
    protected $listeners = [];


    /**
     * @param string $eventName
     * @param EventListener $listener
     * @return $this
    */
    public function addListener(string $eventName, EventListener $listener): EventDispatcher
    {
         $this->listeners[$eventName][] = $listener;

         return $this;
    }



    /**
     * @return EventListener[]
    */
    public function getListeners(): array
    {
        return $this->listeners;
    }



    /**
     * Get listeners by event name
     * @param $eventName
     * @return array|mixed
    */
    public function getListenersByEvent($eventName)
    {
        if(! $this->hasListeners($eventName)) {
            return [];
        }

        return $this->listeners[$eventName];
    }



    /**
     * @param string $eventName
     * @return bool
    */
    public function hasListeners(string $eventName): bool
    {
        return isset($this->listeners[$eventName]);
    }



    /**
     * @param object $event
     * @return object|void
    */
    public function dispatch(object $event)
    {
        $eventName = (new ReflectionClass($event))->getShortName();

        if ($event instanceof Event) {
            $eventName = $event->getName();
        }

        foreach ($this->getListenersByEvent($eventName) as $listener) {
             $listener->handle($event);
        }
    }
}