<?php
namespace Laventure\Component\Console\Command;


use Laventure\Component\Console\Command\Contract\CommandInterface;
use Laventure\Component\Console\Command\Contract\CommandStatusInterface;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputBagInterface;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Input\Exception\InputArgumentException;
use Laventure\Component\Console\Input\InputArgument;
use Laventure\Component\Console\Input\InputBag;
use Laventure\Component\Console\Input\InputOption;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use LogicException;


/**
 * Concrete command
 *
 * @Command
*/
class Command
{

     const SUCCESS  = 0;
     const FAILURE  = 1;
     const INVALID  = 2;


     /**
      * @var string
     */
     protected $defaultName;




     /**
      * name of command
      *
      * @var string
     */
     protected $name;




     /**
      * @var string
     */
     protected $description = 'default command do something ...';





     /**
      * Input bag
      *
      * @var InputBag
     */
     protected $inputBag;




     /**
      * Command constructor
      *
      * @param string|null $name
      * @throws CommandException
     */
     public function __construct(string $name = null)
     {
          $this->inputBag = new InputBag();

          if ($name) {
               $this->name($name);
          }

          $this->configure();
    }




    /**
     * implement configuration current command
     *
     * @return void
    */
    public function configure() {}




    /**
     * @param string $name
     * @return $this
     * @throws CommandException
    */
    public function name(string $name): Command
    {
        $this->name = $this->resolveName($name);

        return $this;
    }




    /**
     * return name of command
     *
     * @return string
    */
    public function getName(): string
    {
         return $this->name ?? $this->defaultName;
    }



    /**
     * @return InputBag
    */
    public function getInputBag(): InputBag
    {
         return $this->inputBag;
    }




    /**
     * @param string $description
     * @return $this
    */
    public function description(string $description): Command
    {
        $this->description = $description;

        return $this;
    }




    /**
    * @return string
    */
    public function getDescription(): string
    {
         return $this->description;
    }




    /**
     * @param string $name
     * @param int|null $mode
     * @param string $description
     * @param string $default
     * @return $this
    */
    public function withArgument(string $name, int $mode = null, string $description = '', string $default = ''): Command
    {
         $this->inputBag->addArgument(
             new InputArgument($name, $mode, $description, $default)
         );

         return $this;
    }


    /**
     * add option
     *
     * @param string $name
     * @param int|null $mode
     * @param string $description
     * @param null $default
     * @return $this
    */
    public function withOption(string $name, int $mode = null, string $description = '', $default = null): Command
    {
        $this->inputBag->addOption(
            new InputOption($name, $mode, $description, $default)
        );

        return $this;
    }



    /**
     * @return array
    */
    public function getOptions(): array
    {
         return $this->inputBag->getOptions();
    }





    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
          throw new LogicException(
              'You must override the execute() method in the concrete command class.'. get_called_class()
          );
    }





    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $input->withArgumentsBag($this->inputBag->getArguments());
        $input->withOptionsBag($this->inputBag->getOptions());

        return $this->execute($input, $output);
    }



    /**
     * @param $name
     * @return string
     * @throws CommandException
    */
    private function resolveName($name): string
    {
         if (! $this->validateCommandName($name)) {
              throw new CommandException("Invalid name '{$name}' for command ". get_called_class());
         }

         return $name;
    }



    /**
     * @param $name
     * @return false
    */
    private function validateCommandName($name): bool
    {
         return stripos($name, ':') !== false;
    }




    /**
     * @return bool
    */
    public function isValidName(): bool
    {
        return $this->validateCommandName($this->name);
    }

}