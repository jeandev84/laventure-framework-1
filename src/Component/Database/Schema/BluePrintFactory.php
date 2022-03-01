<?php
namespace Laventure\Component\Database\Schema;


use Laventure\Component\Database\Schema\Column\Contract\BluePrintColumnInterface;
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
       * @return BluePrintColumnInterface
       * @throws BluePrintFactoryException
      */
      public function make(): BluePrintColumnInterface
      {
            switch ($this->connectionName) {
                case 'mysql':
                    return new MysqlBluePrintColumn();
                break;
                case 'pgsql':
                    return new PostgresqlBluePrintColumn();
                break;
            }

            throw new BluePrintFactoryException("unable blue print for connection {$this->connectionName}");
      }
}