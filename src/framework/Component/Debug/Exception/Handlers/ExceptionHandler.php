<?php
namespace Laventure\Component\Debug\Exception\Handlers;


use Exception;
use Laventure\Component\Debug\Exception\Logger\ErrorLogger;
use Laventure\Component\Debug\Exception\Logger\ErrorLoggerInterface;



/**
 * @ExceptionHandler
*/
abstract class ExceptionHandler implements ExceptionHandlerInterface
{

    /**
     * @var Exception
    */
    protected $exception;




    /**
     * @var ErrorLoggerInterface
    */
    protected $logger;





    /**
     * @param ErrorLoggerInterface|null $logger
    */
    public function __construct(ErrorLoggerInterface $logger = null)
    {
         if (! $logger) {
             $logger = new ErrorLogger();
         }

         $this->logger = $logger;
    }




    /**
     * @inheritDoc
    */
    public function setException(Exception $exception)
    {
          $this->exception = $exception;
    }




    /**
     * @return void
    */
    public function logErrors()
    {
         $this->logger->log($this->exception);
    }




    /**
     * @inheritDoc
    */
    abstract public function handle();
}