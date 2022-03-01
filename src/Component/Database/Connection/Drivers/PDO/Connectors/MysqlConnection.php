<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;

use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;
use LogicException;


/**
 * @MysqlConnection
*/
class MysqlConnection extends PdoConnection
{

    /**
     * @return string
    */
    public function getName(): string
    {
        return 'mysql';
    }


    /**
     * @inheritDoc
     * @throws LogicException
    */
    public function createDatabase()
    {
         $sqlGeneric = sprintf('CREATE DATABASE %s IF NOT EXIST;', $this->config['database']);

         $this->exec($sqlGeneric);
    }



    /**
     * @inheritDoc
    */
    public function dropDatabase()
    {
        return "";
    }





    /**
     * @inheritDoc
     * @throws LogicException
    */
    public function createTable($table, string $criteria)
    {
         $sqlGeneric = sprintf(
      "CREATE TABLE IF NOT EXISTS %s (%s) 
             ENGINE=%s DEFAULT CHARSET=%s;",
             $table,
             $criteria,
             $this->config['engine'],
             $this->config['charset']
         );

         $this->exec($sqlGeneric);
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