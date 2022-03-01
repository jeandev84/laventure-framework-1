<?php
namespace Laventure\Foundation\Console;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Console;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Contract\Console\Kernel as ConsoleKernelContract;
use Laventure\Foundation\Application\Application;
use Laventure\Foundation\Console\Logger\ConsoleLogger;
use Laventure\Foundation\Console\Storage\CommandStack;


/**
 * @Kernel
*/
class Kernel implements ConsoleKernelContract
{


       /**
         * @var Application
       */
       protected $app;





       /**
        * @var Console
       */
       protected $console;





       /**
        * @var FileSystem
       */
       protected $fileSystem;




       /**
        * @var array
       */
       protected $commands = [];





      /**
       * @var array
      */
      protected $defaultCommands = [];




      /**
       * @param Application $app
       * @param Console $console
       * @param FileSystem $fileSystem
      */
      public function __construct(Application $app, Console $console, FileSystem $fileSystem)
      {
            $this->app        = $app;
            $this->console    = $console;
            $this->fileSystem = $fileSystem;
      }




      /**
       * @inheritDoc
      */
      public function handle(InputInterface $input, OutputInterface $output): int
      {
          try {

              $commands = $this->getResolvedCommands();
              $this->console->addCommands($commands);

              return $this->console->run($input, $output);

          } catch (\Exception $e) {

               $logger = new ConsoleLogger();
               $logger->logErrors($e);

               exit(0);
          }
      }



     /**
       * @inheritDoc
     */
     public function terminate(InputInterface $input, $status)
     {
            /*
            $serverLocal = new ServerLocalReader();
            $serverLocal->runLocal($this->fileSystem);
            */
            switch ($status) {
                case 0:
                     // echo "Successfully command.\n";
                  break;
                case 1:
                    // echo "Failed command.\n";
                break;
                default:
                    // echo "Something went wrong\n";
                break;
           }

           return $status;
     }





     /**
       * @throws \Exception
     */
     protected function getResolvedCommands(): array
     {
         $commands = $this->getCommandStackToResolve();

         $resolvedCommands = [];

         foreach ($commands as $command) {
             if (is_string($command)) {
                 $command = $this->app->get($command);
             }
             if ($command instanceof Command) {
                 $resolvedCommands[] = $command;
             }
         }

         return $resolvedCommands;
     }




    /**
     * @return array
    */
    protected function getStackGeneratedCommands(): array
    {
        $commandNames = $this->fileSystem->loadResourceFileNames('app/Console/Command/*.php');

        $commands = [];

        foreach ($commandNames as $commandName) {

            $commands[] = "App\\Console\\Command\\$commandName";
        }

        return $commands;
    }


    /**
     * @return array
    */
    protected function getCommandStackToResolve(): array
    {
          return array_merge($this->getBaseCommands(),
              $this->getStackGeneratedCommands(),
              $this->commands
          );
    }




     /**
      * @return array
     */
     protected function getBaseCommands(): array
     {
         return CommandStack::getDefaultCommands();
     }
}