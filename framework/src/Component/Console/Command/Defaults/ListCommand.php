<?php
namespace Laventure\Component\Console\Command\Defaults;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Command\Contract\ListableCommandInterface;
use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;


/**
 * todo refactoring
 *
 * @ListCommand
*/
class ListCommand extends Command implements ListableCommandInterface
{

     /**
      * @var string
     */
     protected $defaultName = 'list';



     /**
      * @var string
     */
     protected $description = 'describe all available commands of the system.';



     /**
      * @var array
     */
     protected $commands = [];




     /**
      * @param array $commands
      * @return void
     */
     public function setCommandList(array $commands)
     {
          $this->commands = $commands;
     }


     /**
      * @param InputInterface $input
      * @param OutputInterface $output
      * @return int
      */
     protected function execute(InputInterface $input, OutputInterface $output): int
     {
           $this->showCommandListInformation($output);

           return Command::SUCCESS;
     }




     /**
      * @param OutputInterface $output
      * @return void
      */
     protected function showCommandListInformation(OutputInterface $output)
     {
          $output->writeln($this->displayHeaderBlockInformation());

          $defaultCommands = [];
          $commandNamed    = [];

          /** @var Command $command */
          foreach ($this->commands as $name => $command) {
                  if (stripos($name, ':') !== false) {
                      $name = explode(':', $name, 2)[0];
                      $commandNamed[$name][] = $command;
                  }else{
                      $defaultCommands[] = $name;
                  }
          }


          $output->writeln("List of Commands:");

          if ($defaultCommands) {
             foreach ($defaultCommands as $cmd) {
                 $output->writeln(" ". $cmd);
             }
          }

          if ($commandNamed) {

              $maxCmdLength = $this->maxLengthCommands($commandNamed);

              foreach ($commandNamed as $name => $commands) {

                   $output->writeln($name);
                   foreach ($commands as $cmd) {
                       // $description = substr($cmd->getDescription(), $maxCmdLength, strlen($cmd->getDescription()));
                       // $name = substr($cmd->getName(), 0, $maxCmdLength). "  ";
                       $description = $cmd->getDescription();;
                       $name = $cmd->getName();

                       // $str = printf("|[%s] [%s] \n", $name, $description);
                       $str = sprintf(' %s               %s', $name, $description);
                       $output->writeln($str);
                   }
              }
          }

          $output->writeln($this->displayEndBlockInformation());
     }


     private function maxLengthCommands($commandNamed)
     {
         $cmdLength = [];

         foreach ($commandNamed as $commands) {
             foreach ($commands as $cmd) {
                 $cmdLength[] = strlen($cmd->getName());
             }
         }

         return max($cmdLength);
     }


     /**
      * @param string $filename
      * @param array $replacements
      * @return array|false|string|string[]
     */
     protected function generateStub(string $filename, array  $replacements)
     {
         $content = file_get_contents(__DIR__."/stub/$filename.stub");

         return str_replace(
             array_keys($replacements),
             array_values($replacements),
             $content
         );
     }

     /**
      * @return false|string
     */
     private function displayHeaderBlockInformation()
     {
         return file_get_contents(__DIR__ . '/stub/command_header.stub');
     }




     /**
      * @return string
     */
     private function displayEndBlockInformation(): string
     {
          return "End";
     }


    /**
     * @return array
     */
    public function getCommandList(): array
    {
        return $this->commands;
    }


}