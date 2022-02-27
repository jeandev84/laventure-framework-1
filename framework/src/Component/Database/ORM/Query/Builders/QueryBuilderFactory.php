<?php
namespace Laventure\Component\Database\ORM\Query\Builders;

use Laventure\Component\Database\ORM\EntityManager;


/**
 * @QueryBuilderFactory
*/
class QueryBuilderFactory
{

      /**
       * @throws \Exception
      */
      public static function make(EntityManager $em)
      {
            $connection = $em->getPdoConnection();

            switch ($name = $connection->getName()) {
                case 'mysql':
                     return new MysqlQueryBuilder($em);
                 break;
                case 'pgsql':
                    return new PostgresQueryBuilder($em);
                break;
            }

          throw new \Exception("unable connection driver {$name}");
      }
}