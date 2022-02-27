<?php
namespace Laventure\Component\Routing\Exception;



use Throwable;


/**
 * @InvalidMethodException
*/
class RouteCallbackException extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
    */
    public function __construct(string $message = "", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}