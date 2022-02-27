<?php
namespace Laventure\Component\Debug\Exception;


use Laventure\Component\Debug\Exception\Handlers\ExceptionHandlerInterface;

/**
 * @InspectorFactory
*/
class HandlerFactory
{

    /**
     * @param $handler
     * @return ErrorHandlerInterface|ExceptionHandlerInterface|mixed
    */
    public static function map($handler)
    {
        if ($handler instanceof ExceptionHandlerInterface) {
            \set_exception_handler([$handler, 'setException']);
        }

        if ($handler instanceof ErrorHandlerInterface) {
            \set_error_handler([$handler, 'setError']);
        }

        return $handler;
    }
}