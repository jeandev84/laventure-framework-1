<?php
namespace Laventure\Component\EventDispatcher\Contract;

/**
 * @ListenerProviderInterface
*/
interface ListenerProviderInterface
{
    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
    */
    public function getListenersForEvent(object $event) : iterable;
}