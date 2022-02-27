<?php
namespace Laventure\Component\Http\Session\Contract;


/**
 * @FlashInterface
*/
interface FlashInterface
{
      /**
       * @param string $type
       * @param string $message
       * @return mixed
      */
      public function add(string $type, string $message);




      /**
       * @param string $name
       * @return mixed
      */
      public function get(string $name);





      /**
       * @return mixed
      */
      public function getMessages();
}