<?php
namespace Laventure\Component\Debug\Exception\Handlers;


use Exception;
use Laventure\Component\Debug\Exception\ErrorHandlerInterface;


/**
 * @ExceptionHandlerInterface
*/
interface ExceptionHandlerInterface extends ErrorHandlerInterface
{

      /**
       * @return mixed
      */
      public function setException(Exception $exception);
}