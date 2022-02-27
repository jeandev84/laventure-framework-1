<?php
namespace Laventure\Component\Database\Connection;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\MysqlConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\OracleConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\PgsqlConnection;
use Laventure\Component\Database\Connection\Drivers\PDO\Connectors\SqliteConnection;


/**
 * @ConnectionBag
*/
class ConnectionBag
{


    /**
     * @var array
    */
    protected $drivers = [];



    /**
     * @var array
    */
    protected static $defaultConnections = [];



    /**
     * ConnectionCollection constructor
    */
    public function __construct()
    {
        $this->setDefaultConnections($this->getDefaultConnections());
    }



    /**
     * @param ConnectionInterface $connection
     * @return ConnectionInterface
    */
    public function add(ConnectionInterface $connection): ConnectionInterface
    {
        $this->drivers[$connection->getName()] = $connection;

        return $connection;
    }




    /**
     * @param array $connections
     * @return void
    */
    public function addConnections(array $connections)
    {
          foreach ($connections as $connection) {
               $this->add($connection);
          }
    }



    /**
     * @param $name
     * @param null $default
     * @return ConnectionInterface|null
    */
    public function get($name, $default = null): ?ConnectionInterface
    {
        return $this->drivers[$name] ?? $default;
    }





    /**
     * @param $name
     * @return bool
    */
    public function has($name): bool
    {
        return isset($this->drivers[$name]);
    }





    /**
     * @return array
    */
    public function all(): array
    {
        return $this->drivers;
    }




    /**
     * @param $name
     * @return void
    */
    public function remove($name)
    {
        unset($this->drivers[$name]);
    }


    /**
     * @param array $connectors
     * @return void
    */
    public function setDefaultConnections(array $connectors)
    {
        foreach ($connectors as $connector) {
             if ($connector instanceof ConnectionInterface) {
                 static::$defaultConnections[$connector->getName()] = $connector;
                 $this->add($connector);
             }
        }
    }



    /**
     * @return string[]
    */
    public static function getDefaultNames(): array
    {
          return array_keys(self::$defaultConnections);
    }



    /**
     * @return array
    */
    protected function getDefaultConnections(): array
    {
         return [
              new MysqlConnection(),
              new PgsqlConnection(),
              new SqliteConnection(),
              new OracleConnection(),
         ];
    }
}