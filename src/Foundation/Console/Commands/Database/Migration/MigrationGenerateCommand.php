<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;

use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\MigrationCommand;
use Laventure\Foundation\Service\Generator\Exception\StubGeneratorException;


/**
 * @MigrationGenerateCommand
*/
class MigrationGenerateCommand extends MigrationCommand
{

     /**
      * @var string
     */
     protected $name = 'make:migration';


     /**
      * @var string
     */
     protected $description = "make migration class ...";


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws StubGeneratorException
     */
     protected function execute(InputInterface $input, OutputInterface $output): int
     {
          if($path = $this->migrationStub->generateMigrationFile()) {
              $output->writeln(sprintf('Migration %s created successfully...', $path));
          }

          return Command::SUCCESS;
     }
}