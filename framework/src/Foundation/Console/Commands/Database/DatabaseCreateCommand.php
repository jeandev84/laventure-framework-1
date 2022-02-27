<?php
namespace Laventure\Foundation\Console\Commands\Database;

use Laventure\Component\Config\Config;
use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Common\DatabaseCommand;


/**
 * @DatabaseCreateCommand
*/
class DatabaseCreateCommand extends DatabaseCommand
{

      /**
       * @var string
       */
       protected $name = 'database:create';



       /**
        * @var string
       */
       protected $description = 'create a new database.';




       /**
        * @param InputInterface $input
        * @param OutputInterface $output
        * @return int
        * @throws \Exception
       */
       protected function execute(InputInterface $input, OutputInterface $output): int
       {
            $this->database->create();

           $currentConn = $this->database->getDefaultConnection();
           /* dd($this->app[Config::class]['database'][$currentConn]['database']); */

           $databaseName = $this->app[Config::class]['database'][$currentConn]['database'];

           $output->writeln("Database '{$databaseName}' was been created successfully!");

            return Command::SUCCESS;
       }

}