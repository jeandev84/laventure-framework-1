<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO;


use Laventure\Component\Database\Connection\Drivers\PDO\Exception\PdoDriverConnectionException;
use Laventure\Component\Database\Connection\Exception\ConnectionLogicException;
use PDO;


/**
 * @Connection
*/
class Connection
{

      /**
       * @var array
      */
      protected static $defaultOptions = [
          PDO::ATTR_PERSISTENT          => true,
          PDO::ATTR_EMULATE_PREPARES    => 0,
          PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_OBJ,
          PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION
     ];




     /**
      * @param array $config
      * @return PDO
      * @throws ConnectionLogicException
     */
     public static function make(array $config): PDO
     {
         try {

             $pdo = new PDO($config['dsn'], $config['username'], $config['password']);

             $config['options'][] = sprintf("SET NAMES '%s'", $config['charset'] ?? 'utf8');

             foreach ($config['options'] as $option) {
                 $pdo->exec($option);
             }

             foreach (static::$defaultOptions as $key => $value) {
                 $pdo->setAttribute($key, $value);
             }

             return $pdo;

         } catch (\PDOException $e) {

             /* echo $e->getMessage(); */

             throw new ConnectionLogicException($e->getMessage());
         }
     }



     /**
      * @param $connection
      * @return mixed
      * @throws PdoDriverConnectionException
     */
     public static function has($connection)
     {
         if (! \in_array($connection, PDO::getAvailableDrivers())) {
             throw new PdoDriverConnectionException('unable connection driver ('. $connection .')');
        }

        return $connection;
     }
}