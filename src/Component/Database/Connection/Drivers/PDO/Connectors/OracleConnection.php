<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;


use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;


/**
 * @OracleConnection
*/
class OracleConnection extends PdoConnection
{

    /**
     * @return string
    */
    public function getName(): string
    {
        return 'oci';
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
    public function createTable($table, string $printColumns)
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
}