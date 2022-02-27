<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Foundation\Console\Commands\Database\Migration\Common\MigrationCommand;


/**
 * @MigrationInstallCommand
 */
class MigrationInstallCommand extends MigrationCommand
{

    /**
     * @var string
    */
    protected $name = 'migration:install';



    /**
     * @var string
    */
    protected $description = "create the migration table .";




    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->migrator->install();

        $output->writeln("Migration version table successfully created!");

        return Command::SUCCESS;
    }

}