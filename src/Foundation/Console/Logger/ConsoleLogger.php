<?php
namespace Laventure\Foundation\Console\Logger;


use Exception;
use Laventure\Foundation\Exception\LaventureLoggerInterface;



/**
 * @ConsoleException
*/
class ConsoleLogger implements LaventureLoggerInterface
{


    /**
     * @param Exception $exception
     * @return string
    */
    public function logErrors(Exception $exception): string
    {
        $message = [
           sprintf('Message : %s', $exception->getMessage()),
           sprintf('Line    : %s', $exception->getLine())
        ];

        return join(PHP_EOL, $message);
    }



    public function log()
    {
        // TODO: Implement log() method.
    }
}