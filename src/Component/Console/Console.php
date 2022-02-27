<?php
namespace Laventure\Component\Console;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\CommandCollection;
use Laventure\Component\Console\Command\Contract\ListableCommandInterface;
use Laventure\Component\Console\Command\Defaults\HelpCommand;
use Laventure\Component\Console\Command\Defaults\ListCommand;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;


/**
 * Invoker command execute concrete command => Receiver it's the actions
 *
 * @Console
*/
class Console implements ConsoleInterface
{

    use ConsoleTrait;


    /**
     * @var CommandCollection
    */
    protected $commands;





    /**
     * @throws CommandException
    */
    public function __construct(array $commands = [])
    {
         $commands = array_merge($this->getDefaultCommands(), $commands);

         $this->commands = new CommandCollection($commands);
    }




     /**
      * @param array $commands
      * @return void
      * @throws CommandException
     */
     public function addCommands(array $commands)
     {
          foreach ($commands as $command) {
              $this->addCommand($command);
          }
     }



      /**
       * @param string $name
       * @return bool
      */
      public function hasCommand(string $name): bool
      {
            return $this->commands->hasCommand($name);
      }


     /**
      * @param Command $command
      * @return Command
      * @throws CommandException
     */
     public function addCommand(Command $command): Command
     {
          return $this->commands->add($command);
     }




    /**
     * @param $name
     * @return Command
     */
    public function retrieveCommand($name): Command
    {
        return $this->commands->getCommand($name);
    }





    /**
     * Remove command
     *
     * @param string $name
    */
    public function removeCommand(string $name)
    {
         $this->commands->removeCommand($name);
    }



    /**
     * @param array $names
    */
    public function removeCommands(array $names)
    {
        foreach ($names as $name) {
            $this->removeCommand($name);
        }
    }





    /**
     * @return Command[]
    */
    public function getCommands(): array
    {
         return $this->commands->getCommands();
    }




    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
            $commandName = $input->getFirstArgument();

            if (! $commandName) {
                $commandName = $this->listCommand();
            }elseif (\in_array($commandName, ['-h', '--help'])) {
                $commandName = $this->helpCommand();
            }


            if (! $this->hasCommand($commandName)) {
                 $output->write("Invalid command '{$commandName}'\n");
                 $output->send();
                 return Command::FAILURE;
            }

            $command = $this->retrieveCommand($commandName);

            if ($command instanceof ListableCommandInterface) {
                $this->commands->removeCommand($command->getName());
                $command->setCommandList($this->getCommands());
            }

            $status = $command->run($input, $output);

            $output->send();

            return $status;
    }




    /**
     * @return ListCommand[]
    */
    public function getDefaultCommands(): array
    {
        return [
            new ListCommand(),
            new HelpCommand()
        ];
    }

}