<?php
namespace Laventure\Foundation\Console\Commands\Database\ORM;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Foundation\Console\Commands\Database\ORM\Service\EntityStubGenerator;


/**
 * @MakeEntityCommand
*/
class MakeEntityCommand extends Command
{

    /**
     * @var string
    */
    protected $name = 'make:entity';



    /**
     * @var string
    */
    protected $description = "make entity class ...";



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
        $entityClass = $input->getArgument();

        if (! $entityClass) {
            $output->writeln("Entity name is required.");
            return Command::FAILURE;
        }

        if ($message = $this->entitySubGenerator->generateEntity($entityClass)) {
            $output->writeln($message);
        }

        return Command::SUCCESS;
    }
}