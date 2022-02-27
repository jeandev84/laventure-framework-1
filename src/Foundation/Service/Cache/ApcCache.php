<?php
namespace Laventure\Foundation\Service\Cache;


use Laventure\Foundation\Service\Cache\Contract\CacheInterface;

/**
 * @ApcCache
*/
class ApcCache implements CacheInterface
{

    /**
     * @inheritDoc
     */
    public function set(string $key, $data)
    {
        // TODO: Implement set() method.
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key)
    {
        // TODO: Implement exists() method.
    }
}