<?php
namespace Laventure\Component\Console\Command;


/**
 * @CommandValidator
*/
class CommandValidator
{
    /**
     * @param string $name
     * @return false|int
    */
    public function validate(string $name)
    {
        return preg_match("/^(\w+):(\w+)$/i", trim($name));
    }
}