<?php
namespace Laventure\Foundation\Console\Commands\Database\Common;

use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Database\Managers\Manager;
use Laventure\Foundation\Application\Application;

/**
 * @DatabaseCommand
*/
abstract class DatabaseCommand extends Command
{


     /**
      * @var Application
     */
     protected $app;


     /**
      * @var Manager
     */
     protected $database;


     /**
      * @param Application $app
      * @param Manager $database
      * @param string|null $name
      * @throws CommandException
     */
     public function __construct(Application $app, Manager $database, string $name = null)
     {
         parent::__construct($name);
         $this->app = $app;
         $this->database = $database;
     }
}