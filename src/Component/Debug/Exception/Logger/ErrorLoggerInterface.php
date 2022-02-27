<?php
namespace Laventure\Component\Debug\Exception\Logger;


use Exception;

/**
 * @ErrorLoggerInterface
*/
interface ErrorLoggerInterface
{


    /**
     * @param string $destination
     * @return mixed
    */
    public function withDestination(string $destination);




    /**
     * @return void
    */
    public function log(Exception $exception);
}