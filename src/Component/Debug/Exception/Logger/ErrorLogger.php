<?php
namespace Laventure\Component\Debug\Exception\Logger;


use Exception;


/**
 * @ErrorLogger
*/
class ErrorLogger implements ErrorLoggerInterface
{



    /**
     * @var string
     */
    protected $destination;




    /**
     * @param string $destination
     * @return $this
    */
    public function withDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }




    /**
     * @param Exception $exception
     * @return void
    */
    public function log(Exception $exception)
    {
        \error_log(
            $this->getMessage($exception),
            $this->getMessageType(),
            $this->getDestination(),
            $this->getAdditionalHeaders()
        );
    }



    /**
     * @param Exception $e
     * @return string
    */
    protected function getMessage(Exception $e): string
    {
         $message[]  =  "[%s]";
         $message[]  =  "Message error: %s";
         $message[]  =  "| File: %s |";
         $message[]  =  "Line : %s\n=============\n";

         return sprintf(implode($message), date('Y-m-d H:i:s'), $e->getMessage(), $e->getFile(), $e->getLine());
    }



    /**
     * @return int
    */
    protected function getMessageType(): int
    {
          return 3;
    }



    /**
     * @return string
    */
    protected function getDestination(): string
    {
          if ($this->destination) {
              return $this->destination;
          }

          return $this->destination = __DIR__.'/errors/log';
    }




    /**
     * @return string
    */
    protected function getAdditionalHeaders(): string
    {
        return '';
    }
}