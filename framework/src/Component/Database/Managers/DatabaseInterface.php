<?php
namespace Laventure\Component\Database\Managers;


/**
 * @DatabaseInterface
*/
interface DatabaseInterface
{

      /**
       * @param array $config
       * @return mixed
      */
      public function open(array $config);



      /**
        * @return mixed
      */
      public function getConnection();




      /**
       * @return mixed
      */
      public function close();
}