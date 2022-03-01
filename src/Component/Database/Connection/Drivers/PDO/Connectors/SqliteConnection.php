<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;


use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;


/**
 * @SqliteConnection
*/
class SqliteConnection extends PdoConnection
{

    /**
     * @return string
    */
    public function getName(): string
    {
        return 'sqlite';
    }



    /**
     * @param $config
     * @return string
    */
    protected function makeDSN($config): string
    {
         return sprintf('%s:%s', $config['driver'], $config['database']);
    }



    /**
     * @return string|null
    */
    public function getUsername(): ?string
    {
         return null;
    }



    /**
     * @return string|null
    */
    public function getPassword(): ?string
    {
        return null;
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
    public function createTable($table, string $criteria)
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