<?php
namespace Laventure\Component\Database\Connection;


use Exception;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryEntityMapperInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Contract\QueryResultInterface;
use Laventure\Component\Database\Connection\Exception\QueryFactoryException;
use Laventure\Component\Database\Connection\Drivers\PDO\Statement\Query as PdoQuery;
use Laventure\Component\Database\Connection\Drivers\Mysqli\Statement\Query as MysqliQuery;


/**
 * @QueryFactory
*/
class QueryFactory
{

    /**
     * @param ConnectionInterface $connection
     * @param null $entity
     * @return QueryInterface
    */
    public static function make(ConnectionInterface $connection, $entity = null): QueryInterface
    {
            $query  = $connection->createQuery();

            if ($entity && $query instanceof QueryEntityMapperInterface) {
                $query->withEntity($entity);
            }

            return $query;
    }
}