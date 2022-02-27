<?php
namespace Laventure\Foundation\Console\Commands\Routing;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Component\Routing\Resource\ApiResource;
use Laventure\Component\Routing\Resource\WebResource;
use Laventure\Foundation\Console\Commands\Routing\Service\ModuleGenerator;
use Laventure\Foundation\Console\Commands\Routing\Service\ResourceGenerator;


/**
 * @MakeResourceCommand
*/
class MakeResourceCommand extends Command
{

    /**
     * @var string
     */
    protected $name = "make:resource";



    /**
     * @var string
     */
    protected $description = "make a new resource. $ php console make:resource Product";



    /**
     * @var ModuleGenerator
    */
    protected $generator;




    /**
     * @param ModuleGenerator $generator
     * @param string|null $name
     * @throws CommandException
    */
    public function __construct(ModuleGenerator $generator, string $name = null)
    {
        parent::__construct($name);
        $this->generator = $generator;
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
             $output->writeln("Entity class name is required.");
             return Command::FAILURE;
          }

          if($input->hasFlag('api')) {
              $this->generator->generateResourceApi($entityClass);
          }else {
              $this->generator->generateResource($entityClass);
          }

          foreach ($this->generator->getMessageLog() as $messageLog) {
               $output->writeln($messageLog);
          }

          return Command::SUCCESS;
    }

}