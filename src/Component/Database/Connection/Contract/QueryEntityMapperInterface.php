<?php
namespace Laventure\Component\Database\Connection\Contract;


/**
 * @QueryEntityMapperInterface
*/
interface QueryEntityMapperInterface
{
     /**
      * @param string $entityClass
      * @return mixed
     */
     public function withEntity(string $entityClass);
}