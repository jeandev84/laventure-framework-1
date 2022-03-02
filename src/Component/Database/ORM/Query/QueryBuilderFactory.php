<?php
namespace Laventure\Component\Database\ORM\Query;

use Exception;
use Laventure\Component\Database\ORM\Common\EntityManager;
use Laventure\Component\Database\ORM\Query\Builders\MysqlQueryBuilder;
use Laventure\Component\Database\ORM\Query\Builders\PostgresQueryBuilder;


/**
 * @QueryBuilderFactory
*/
class QueryBuilderFactory
{

      /**
        * @param EntityManager $em
        * @return QueryBuilder
        * @throws Exception
      */
      public static function make(EntityManager $em): QueryBuilder
      {
            $connection = $em->getConnectionManager();

            switch ($name = $connection->getName()) {
                case 'mysql':
                     return new MysqlQueryBuilder($em);
                 break;
                case 'pgsql':
                    return new PostgresQueryBuilder($em);
                break;
            }

          throw new Exception("unable connection driver {$name}");
      }
}