<?php
namespace Laventure\Component\Database\ORM\Contract;


/**
 * @Flushable
*/
interface Flushable
{
    public function flush();
}