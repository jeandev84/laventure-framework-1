<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\MigrationCommand;


/**
 * @MigrationResetCommand
*/
class MigrationResetCommand extends MigrationCommand
{

    /**
     * @var string
    */
    protected $name = 'migration:reset';





    /**
     * @var string
    */
    protected $description = "clean up all applied migrations and delete all files .";




    /**
     * @throws \Exception
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->migrator->reset();
        $output->writeln("Migration successfully reset.");

        return Command::SUCCESS;
    }
}