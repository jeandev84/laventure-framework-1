<?php
namespace Laventure\Component\Console\Command\Contract;


/**
 * @CommandCollectionInterface
*/
interface CommandCollectionInterface
{

     /**
      * @return mixed
     */
     public function getCommands();



     /**
      * @param $name
      * @return mixed
     */
     public function getCommand($name);
}