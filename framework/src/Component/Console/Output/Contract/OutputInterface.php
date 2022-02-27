<?php
namespace Laventure\Component\Console\Output\Contract;


/**
 * @OutputInterface
*/
interface OutputInterface
{
      public function write(string $message);
      public function writeln(string $message);
      public function exec(string $command);
      public function printFormat(string $mask);
      public function getMessages();
      public function send();
}