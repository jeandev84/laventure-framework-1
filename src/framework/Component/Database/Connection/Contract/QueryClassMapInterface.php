<?php
namespace Laventure\Component\Database\Connection\Contract;


/**
 * @QueryClassMapInterface
*/
interface QueryClassMapInterface
{
     /**
      * @param string $entityClass
      * @return mixed
     */
     public function withEntity(string $entityClass);
}