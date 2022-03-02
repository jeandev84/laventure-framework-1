<?php
namespace Laventure\Component\Database\Connection\Contract;



/**
 * @QueryInterface
*/
interface QueryInterface extends QueryResultInterface
{

     /**
      * @param string $sql
      * @param array $params
      * @return mixed
     */
     public function prepare(string $sql, array $params = []);




     /**
      * @return mixed
     */
     public function execute();



     /**
      * @return mixed
     */
     public function errors();
}