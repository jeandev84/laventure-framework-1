<?php
namespace Laventure\Component\Debug\Exception\Handlers;


use ErrorException;
use Laventure\Component\Debug\Exception\ErrorHandlerInterface;

/**
 * @ErrorExceptionHandlerInterface
*/
interface ErrorExceptionHandlerInterface extends ErrorHandlerInterface
{

      /**
       * @param ErrorException $exception
       * @return mixed
      */
      public function setError(ErrorException $exception);
}