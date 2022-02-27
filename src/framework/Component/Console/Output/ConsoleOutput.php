<?php
namespace Laventure\Component\Console\Output;


use Laventure\Component\Console\Output\Contract\OutputInterface;



/**
 * @ConsoleOutput
*/
class ConsoleOutput implements OutputInterface
{


    /**
     * @var array
    */
    protected $messages = [];



    /**
     * @param string $message
     * @return self
    */
    public function write(string $message): self
    {
         $this->messages[] = $message;

         return  $this;
    }



    /**
     * @param string $message
     * @return self
    */
    public function writeln(string $message): self
    {
        return $this->write($message . "\n");
    }




    /**
     * @param string $command
     * @return false|string|null
    */
    public function exec(string $command)
    {
        if ($this->messages) {
            echo $this->getMessages() . "\n";
        }

        return shell_exec($command);
    }


    /**
     * @return string
     */
    public function getMessages(): string
    {
        return implode("", $this->messages);
    }



    /**
     * @return void
    */
    public function send()
    {
        echo $this->getMessages();
    }



    public function printFormat(string $mask)
    {
         printf($mask);
    }
}