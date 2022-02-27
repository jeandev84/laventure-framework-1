<?php
namespace Laventure\Component\Database\Connection\Exception;


use Throwable;

/**
 * @StatementException
*/
class StatementException extends \Exception
{
   public function __construct($message = "", $code = 0, Throwable $previous = null)
   {
       parent::__construct($message, $code, $previous);
   }
}