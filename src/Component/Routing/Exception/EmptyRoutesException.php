<?php
namespace Laventure\Component\Routing\Exception;



use Throwable;

/**
 * @EmptyRoutesException
*/
class EmptyRoutesException extends \Exception
{
    public function __construct($message = "no routes to dispatch.", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}