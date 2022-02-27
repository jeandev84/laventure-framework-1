<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\MigrationCommand;


/**
 * @MigrationMigrateCommand
*/
class MigrationMigrateCommand extends MigrationCommand
{

      /**
       * @var string
      */
      protected $name = 'migration:migrate';



      /**
       * @var string
      */
      protected $description = "migrate applied migrations.";





      /**
       * @throws \Exception
      */
      protected function execute(InputInterface $input, OutputInterface $output): int
      {
            $this->migrator->migrate();

            $output->writeln("tables successfully migrated.");

            return Command::SUCCESS;
      }
}