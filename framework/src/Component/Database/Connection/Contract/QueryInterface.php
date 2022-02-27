<?php
namespace Laventure\Component\Database\Connection\Contract;



/**
 * @QueryInterface
*/
interface QueryInterface extends QueryHydrateInterface
{

     /**
      * @param string $sql
      * @return mixed
     */
     public function prepare(string $sql);



     /**
      * @return mixed
     */
     public function execute();
}