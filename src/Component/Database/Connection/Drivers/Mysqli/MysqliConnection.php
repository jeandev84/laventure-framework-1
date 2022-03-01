<?php
namespace Laventure\Component\Database\Connection\Drivers\Mysqli;


use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Drivers\Mysqli\Contract\MysqliConnectionInterface;


/**
 * @MysqliConnection
*/
class MysqliConnection implements MysqliConnectionInterface
{

    public function getName()
    {
        return 'mysqli';
    }

    public function connect($config)
    {
        // TODO: Implement connect() method.
    }

    public function connected(): bool
    {
        // TODO: Implement connected() method.
    }


    public function getMysqli(): \mysqli
    {
        // TODO: Implement getMysqli() method.
    }

    public function query(string $sql, array $params = [])
    {
        // TODO: Implement query() method.
    }

    public function getConnection()
    {
        // TODO: Implement getConnection() method.
    }

    public function getConfiguration($key = null, $default = null)
    {
        // TODO: Implement getConfiguration() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
    }

    public function lastInsertId()
    {
        // TODO: Implement lastInsertId() method.
    }

    public function exec($sql)
    {
        // TODO: Implement exec() method.
    }

    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

    /**
     * @inheritDoc
     */
    public function createDatabase()
    {
        // TODO: Implement createDatabase() method.
    }

    /**
     * @inheritDoc
     */
    public function dropDatabase()
    {
        // TODO: Implement dropDatabase() method.
    }

    /**
     * @inheritDoc
     */
    public function createTable($table, string $columns, array $alterColumns = [])
    {
        // TODO: Implement createTable() method.
    }

    /**
     * @inheritDoc
     */
    public function dropTable($table)
    {
        // TODO: Implement dropTable() method.
    }

    /**
     * @inheritDoc
     */
    public function dropIfExistsTable($table)
    {
        // TODO: Implement dropIfExistsTable() method.
    }

    /**
     * @inheritDoc
     */
    public function truncateTable($table)
    {
        // TODO: Implement truncateTable() method.
    }

    /**
     * @inheritDoc
     */
    public function showTables()
    {
        // TODO: Implement showTables() method.
    }

    /**
     * @inheritDoc
     */
    public function describeTable($table)
    {
        // TODO: Implement describeTable() method.
    }

    public function createQuery(string $sql = null): QueryInterface
    {
        // TODO: Implement createQuery() method.
    }
}