<?php
namespace Laventure\Foundation\Exception;


use Laventure\Component\Debug\Exception\Handlers\ExceptionHandler;
use Laventure\Component\Debug\Exception\Logger\ErrorLoggerInterface;


/**
 * @LaventureExceptionHandler
*/
class LaventureExceptionHandler extends ExceptionHandler
{


    /**
     * @var int
    */
    protected $mode;




    /**
     * @param ErrorLoggerInterface|null $logger
    */
    public function __construct($mode, ErrorLoggerInterface $logger = null)
    {
        parent::__construct($logger);
        $this->mode = $mode;
    }




    /**
     * @inheritDoc
    */
    public function handle()
    {
        // TODO: Implement handle() method.
    }

}