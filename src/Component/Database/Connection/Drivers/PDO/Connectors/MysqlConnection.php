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
         /*
           todo implements this syntax
           Example
           CREATE DATABASE IF NOT EXISTS movies CHARACTER SET latin1 COLLATE latin1_swedish_ci
         */

         $sqlGeneric = sprintf(
       'CREATE DATABASE IF NOT EXIST %s CHARACTER SET %s COLLATE %s;',
              $this->config['database'],
              $this->config->get('charset', 'utf8'),
              $this->config->get('collation', 'utf8_general_ci')
         );

         $this->exec($sqlGeneric);
    }



    /**
     * @inheritDoc
    */
    public function dropDatabase()
    {
        /*
         DROP SCHEMA IF EXISTS databaseName;
        */

        $sqlGeneric = sprintf('DROP DATABASE IF EXISTS %s;', $this->config['database']);

        $this->exec($sqlGeneric);
    }





    /**
     * @inheritDoc
     * @throws LogicException
    */
    public function createTable($table, string $printColumns)
    {
         // todo get columns as array config and create table.
         $sqlGeneric = sprintf(
      "CREATE TABLE IF NOT EXISTS %s (%s) 
             ENGINE=%s DEFAULT CHARSET=%s;",
             $table,
             $printColumns,
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