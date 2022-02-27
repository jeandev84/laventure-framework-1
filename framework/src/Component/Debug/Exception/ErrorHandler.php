<?php
namespace Laventure\Component\Debug\Exception;



use Laventure\Component\Debug\Exception\Handlers\ExceptionHandlerInterface;


/**
 * Manage registration handlers and call method handle()
 *
 * @ErrorHandler
*/
class ErrorHandler
{


      /**
       * @var mixed
      */
      protected $handlers = [];




      /**
       * @param int $status
      */
      public function __construct(int $status = -1)
      {
            $this->report($status);
      }



      /**
       * @param int $status
       * @return void
      */
      public function report(int $status)
      {
           \error_reporting($status);
      }




      /**
       * @param ErrorHandlerInterface $handler
       * @return $this
      */
      public function pushHandler(ErrorHandlerInterface $handler): self
      {
            $this->handlers[] = $handler;

            return $this;
      }




      /**
       * @return void
      */
      public function register()
      {
          foreach ($this->handlers as $handler) {
              $handler = HandlerFactory::map($handler);
              $handler->handle();
          }
      }
}