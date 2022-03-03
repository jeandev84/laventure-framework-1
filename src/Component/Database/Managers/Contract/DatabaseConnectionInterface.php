<?php
namespace Laventure\Component\Database\Managers\Contract;


/**
 * @DatabaseConnectionInterface
*/
interface DatabaseConnectionInterface
{

      /**
       * @param array $credentials
       * @return mixed
      */
      public function open(array $credentials);



      /**
        * @return mixed
      */
      public function getConnection();




      /**
       * @return mixed
      */
      public function close();
}