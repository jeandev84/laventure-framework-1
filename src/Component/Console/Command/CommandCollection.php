<?php
namespace Laventure\Component\Console\Command;


use Laventure\Component\Console\Command\Contract\CommandCollectionInterface;
use Laventure\Component\Console\Command\Exception\CommandException;


/**
 * @CommandCollection
*/
class CommandCollection implements CommandCollectionInterface
{



    /**
     * @var Command[]
    */
    protected $commands = [];




    /**
     * @param array $commands
     * @throws Exception\CommandException
    */
    public function __construct(array $commands = [])
    {
         if ($commands) {
              $this->addCommands($commands);
         }
    }




    /**
     * Add command
     *
     * @param Command $command
     * @return Command
     * @throws Exception\CommandException
    */
    public function add(Command $command): Command
    {
         $this->commands[$command->getName()] = $command;

         return $command;
    }





    /**
     * Add collection commands
     *
     * @param Command[] $commands
     * @return void
     * @throws Exception\CommandException
    */
    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->add($command);
        }
    }





    /**
     * Get all commands
     *
     * @return Command[]
    */
    public function getCommands(): array
    {
         return $this->commands;
    }




    /**
     * Find command in the collection
     *
     * @param string $name
     * @return Command|null
    */
    public function getCommand($name): ?Command
    {
        return $this->commands[$name] ?? null;
    }




     /**
      * Determine given name has a command in collection
      *
      * @param string $name
      * @return bool
     */
     public function hasCommand(string $name): bool
     {
          return isset($this->commands[$name]);
     }


     /**
      * Remove command
      *
      * @param string $name
     */
     public function removeCommand(string $name)
     {
          unset($this->commands[$name]);
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

}