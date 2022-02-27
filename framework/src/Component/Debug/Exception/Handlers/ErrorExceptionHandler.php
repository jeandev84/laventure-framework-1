<?php
namespace Laventure\Component\Debug\Exception\Handlers;


use ErrorException;

/**
 * @ErrorExceptionHandler
*/
abstract class ErrorExceptionHandler implements ErrorExceptionHandlerInterface
{


    /**
     * @var ErrorException
    */
    protected $exception;


    /**
     * @inheritDoc
    */
    public function setError(ErrorException $exception)
    {
          $this->exception = $exception;
    }



    /**
     * @return void
    */
    abstract public function handle();
}