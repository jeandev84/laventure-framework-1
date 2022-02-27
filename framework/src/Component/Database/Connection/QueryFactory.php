<?php
namespace Laventure\Component\Database\Connection;


use Exception;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryHydrateInterface;
use Laventure\Component\Database\Connection\Exception\QueryFactoryException;
use Laventure\Component\Database\Connection\Drivers\PDO\Statement\Query as PDOQuery;
use Laventure\Component\Database\Connection\Drivers\Mysqli\Statement\Query as MySQLiQuery;


/**
 * @QueryFactory
*/
class QueryFactory
{

    /**
     * @param ConnectionInterface $connection
     * @param null $entityClass
     * @param array $params
     * @return QueryHydrateInterface
     * @throws Exception
     */
    public static function make(ConnectionInterface $connection, array $params = [], $entityClass = null): QueryHydrateInterface
    {
        $driverConnection = $connection->getConnection();
        $connectionName = $connection->getName();

        if (\in_array($connectionName, ConnectionBag::getDefaultNames())) {

            $query = new PDOQuery($driverConnection);
            $query->withEntity($entityClass);
            $query->withParams($params);

            return $query;

        } /* elseif($connectionName == 'mysqli') {

                 return new MysqliQuery($driverConnection);
         } */

        throw new QueryFactoryException("Invalid type driver for connection {$connectionName}". __METHOD__);
    }
}