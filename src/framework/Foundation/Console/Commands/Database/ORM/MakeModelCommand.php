<?php
namespace Laventure\Foundation\Console\Commands\Database\ORM;

use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Foundation\Console\Commands\Database\ORM\Service\EntityStubGenerator;


/**
 * @MakeModelCommand
*/
class MakeModelCommand extends Command
{

    /**
     * @var string
    */
    protected $name = 'make:model';



    /**
     * @var string
    */
    protected $description = "make new model ...";





    /**
     * @var EntityStubGenerator
     */
    protected $entitySubGenerator;


    /**
     * @param EntityStubGenerator $entityStubGenerator
     * @param string|null $name
     * @throws CommandException
     */
    public function __construct(EntityStubGenerator $entityStubGenerator, string $name = null)
    {
        parent::__construct($name);
        $this->entitySubGenerator = $entityStubGenerator;
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FileWriterException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $modelClass = $input->getArgument();

        if (! $modelClass) {
            $output->writeln("Model name is required.");
            return Command::FAILURE;
        }

        if ($message = $this->entitySubGenerator->generateModel($modelClass)) {
            $output->writeln($message);
        }

        return Command::SUCCESS;
    }



}