<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\MigrationCommand;


/**
 * @MigrationRollbackCommand
*/
class MigrationRollbackCommand extends MigrationCommand
{

       /**
        * @var string
       */
       protected $name = 'migration:rollback';



      /**
       * @var string
      */
      protected $description = "rollback all migrations drop all created table and truncate the version table .";



      /**
       * @throws \Exception
      */
      protected function execute(InputInterface $input, OutputInterface $output): int
      {
           $this->migrator->rollback();
           $output->writeln("Migration successfully rollback to previous state");

           return Command::SUCCESS;
      }

}