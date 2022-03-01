<?php
namespace Laventure\Component\Database\Schema;


use Closure;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;


/**
 * @Schema
*/
class Schema
{

     /**
      * @var ConnectionInterface
     */
     protected $connection;





     /**
      * @param ConnectionInterface $connection
     */
     public function __construct(ConnectionInterface $connection)
     {
            $this->connection = $connection;
     }




     /**
      * @param string $table
      * @param Closure $closure
      * @return void
     */
     public function create(string $table, Closure $closure)
     {
          return (function () use ($table, $closure) {

               $factory   =  new BluePrintFactory($this->connection->getName());
               $bluePrint =  new BluePrint($factory->make($table));

               $closure($bluePrint);

               $this->connection->createTable($table, $bluePrint->printColumns());

               if ($alteredQueries = $bluePrint->getAlteredColumns()) {
                   foreach ($alteredQueries as $sql) {
                       $this->connection->exec($sql);
                   }
               }

          })();
     }



     /**
      * @param string $table
      * @return false|int
     */
     public function drop(string $table)
     {
          return $this->connection->dropTable($table);
     }





      /**
       * used for mysql
       *
       * @param string $table
       * @return void
     */
     public function dropIfExists(string $table)
     {
          $this->connection->dropIfExistsTable($table);
     }





     /**
      * @param string $table
      * @return false|int
     */
     public function truncate(string $table)
     {
         return $this->connection->truncateTable($table);
     }




     /**
      * @param string $table
     */
     public function truncateCascade(string $table) {}



     /**
      * @param string $sql
      * @return mixed
     */
     public function exec(string $sql)
     {
         return $this->connection->exec($sql);
     }
}