<?php
namespace Laventure\Component\Database\Connection;


use Laventure\Component\Database\Configuration\ConfigurationBag;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\Connection\Exception\ConnectionLogicException;


/**
 * @ConnectionFactory
*/
class ConnectionFactory
{

     /**
      * @var ConnectionBag
     */
     public $connections;





     /**
      * @var ConfigurationBag
     */
     public $configs;




     /**
      * ConnectionFactory constructor
     */
     public function __construct(array $config = [])
     {
         $this->connections = new ConnectionBag();
         $this->configs     = new ConfigurationBag();

         if ($config) {
             $this->configs->merge($config);
         }
     }




     /**
      * @param $name
      * @param array $config
      * @return ConnectionInterface
     */
     public function makeConnection($name, array $config): ConnectionInterface
     {
         if ($connection = $this->connections->get($name)) {
             $connection->connect($config);
         }

         return $connection;
     }


     /**
      * @param ConnectionInterface $connection
      * @return PdoConnectionInterface
     */
     public static function pdoConnection(ConnectionInterface $connection): PdoConnectionInterface
     {
         if ($connection instanceof PdoConnectionInterface) {
             return $connection;
         }

         throw new ConnectionLogicException("This connection '{$connection->getName()}' is not pdo connection.");
     }







     /**
      * @param $name
      * @return void
     */
     public function remove($name)
     {
          $this->connections->remove($name);
          $this->configs->remove($name);
     }
}