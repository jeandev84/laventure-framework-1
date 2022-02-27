<?php
namespace Laventure\Foundation\Console\Commands\Console;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Foundation\Console\Commands\Console\Service\CommandStubGenerator;


/**
 * @MakeCommand
*/
class MakeCommand extends Command
{


    const EXISTED  = 5;
    const CREATED  = 6;



    /**
     * @var string
    */
    protected $name = 'make:command';


    /**
     * @var string
    */
    protected $description = "generate new command ...";




    /**
     * @var CommandStubGenerator
    */
    protected $commandStubGenerator;



    /**
     * @var array
    */
    protected $messages = [
        self::EXISTED => 'Command (%s) already exist!',
        self::CREATED => 'Command %s generated successfully!'
    ];



     /**
      * @param CommandStubGenerator $commandStubGenerator
      * @param string|null $name
    */
    public function __construct(CommandStubGenerator $commandStubGenerator, string $name = null)
    {
          parent::__construct($name);
          $this->commandStubGenerator = $commandStubGenerator;
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception\CommandStubGeneratorException|FileWriterException|CommandException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandName = $input->getArgument();

        if ($message = $this->commandStubGenerator->generateCommand($commandName)) {
            $output->writeln($message);
        }

        return Command::SUCCESS;
    }
}