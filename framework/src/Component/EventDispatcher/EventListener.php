<?php
namespace Laventure\Component\EventDispatcher;

use Laventure\Component\EventDispatcher\Contract\EventListenerInterface;


/**
 * @EventListener
*/
abstract class EventListener implements EventListenerInterface
{

    public function getListenersForEvent(object $event): iterable
    {
        //
    }


    /** @param Event $event */
    abstract public function handle(Event $event);
}