<?php
namespace Laventure\Component\Console\Command\Defaults;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;



/**
 * @HelpCommand
*/
class HelpCommand extends Command
{

    /**
     * @var string
    */
    protected $defaultName = 'help';


    /**
     * @var string
     */
    protected $description = 'give more information each commands.';




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|mixed
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Help command");
        return Command::SUCCESS;
    }
}