<?php
namespace Laventure\Component\Console\Command\Contract;


/**
 * @ListableCommandInterface
*/
interface ListableCommandInterface
{

      /**
       * @param array $commands
       * @return mixed
      */
      public function setCommandList(array $commands);





      /**
       * @return mixed
      */
      public function getCommandList();
}