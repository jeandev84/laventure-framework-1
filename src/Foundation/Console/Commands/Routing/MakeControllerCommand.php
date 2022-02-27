<?php
namespace Laventure\Foundation\Console\Commands\Routing;

use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Component\Routing\Resource\WebResource;
use Laventure\Foundation\Console\Commands\Routing\Service\ModuleGenerator;


/**
 * @MakeControllerCommand
*/
class MakeControllerCommand extends Command
{

    /**
     * @var string
    */
    protected $name = 'make:controller';


    /**
     * @var string
    */
    protected $description = 'make a new controller. php console make:controller';




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
     * Make controller or resource
     *
     * Example 1: php console make:controller DemoController
     * Example 2: php console make:controller DemoController --resource
     * Example 2: php console make:controller DemoController -actions=index,about,contact,portfolio
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FileWriterException
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
          $controllerName = $input->getArgument();

          if (! $controllerName) {
              $msg = sprintf('Controller name is required. example: php console %s FooController', $this->getName());
              $output->writeln($msg);
              return Command::FAILURE;
          }

          $actions = explode(',', $input->getOption('actions'));;

          if($input->hasFlag('resource')) {
              $this->generator->generateResource("", $controllerName);
          } else {
              $this->generator->generateControllerWithActions($controllerName, $actions);
          }

          if ($messageLogs = $this->generator->getMessageLog()) {
             foreach ($messageLogs as $messageLog) {
                $output->writeln($messageLog);
             }
         }

         return Command::SUCCESS;
    }

}