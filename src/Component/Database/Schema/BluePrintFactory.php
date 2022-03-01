<?php
namespace Laventure\Component\Database\Schema;


use Laventure\Component\Database\Schema\Column\BluePrintColumn;
use Laventure\Component\Database\Schema\Column\Drivers\MysqlBluePrintColumn;
use Laventure\Component\Database\Schema\Column\Drivers\PostgresqlBluePrintColumn;
use Laventure\Component\Database\Schema\Exception\BluePrintFactoryException;


/**
 * @BluePrintFactory
*/
class BluePrintFactory
{

      /**
       * @var string
      */
      protected $connectionName;




      /**
       * @param string $connectionName
      */
      public function __construct(string $connectionName)
      {
            $this->connectionName = $connectionName;
      }


      /**
        * @param string $table
        * @return BluePrintColumn
        * @throws BluePrintFactoryException
      */
      public function make(string $table): BluePrintColumn
      {
            switch ($this->connectionName) {
                case 'mysql':
                    return new MysqlBluePrintColumn($table);
                break;
                case 'pgsql':
                    return new PostgresqlBluePrintColumn($table);
                break;
            }

            throw new BluePrintFactoryException("unable blue print for connection {$this->connectionName}");
      }
}