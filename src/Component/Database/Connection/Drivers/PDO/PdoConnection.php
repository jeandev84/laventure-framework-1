<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO;



use ArrayAccess;
use Laventure\Component\Database\Connection\ConnectionTrait;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Statement\Query;
use Laventure\Component\Database\Connection\Exception\ConnectionLogicException;
use Laventure\Component\Database\Connection\Exception\StatementException;
use PDO;


/**
 * @PdoConnection
*/
class PdoConnection implements PdoConnectionInterface
{


      use ConnectionTrait;



      /**
       * @param array $config
      */
      public function __construct(array $config = [])
      {
           if ($config) {
               $this->connect($config);
           }
      }



      /**
       * @param $config
       * @return void
      */
      public function connect($config)
      {
          $config = $this->parseConfigs($config);

          if (! $this->connected()) {

              return (function () use ($config) {

                  $name = $config['driver'];

                  if (Connection::hasInDrivers($name)) {
                      $this->setConnection($this->makeConnection([
                          'dsn'      => $this->makeDSN($config),
                          'username' => $this->getUsername(),
                          'password' => $this->getPassword(),
                          'options'  => $config['options'],
                      ]));
                  }

              })();

          }
      }





     /**
      * @return bool
     */
     public function connected(): bool
     {
         return $this->connection instanceof PDO;
     }




     /**
      * @return void
     */
     public function disconnect()
     {
          $this->connection = null;
     }




     /**
      * @param string|null $sql
      * @return void
     */
     public function createQuery(string $sql = null): Query
     {
           $statement = new Query($this->getPdo());

           if ($sql) {
               $statement->prepare($sql);
           }

           return $statement;
     }




     /**
      * @param string $sql
      * @param array $params
      * @return Query
      * @throws ConnectionLogicException
     */
     public function query(string $sql, array $params = []): Query
     {
          return $this->createQuery($sql)->withParams($params);
     }






     /**
      * @return PDO
      * @throws ConnectionLogicException
     */
     public function getPdo(): PDO
     {
        if (! $this->connected()) {
            throw new ConnectionLogicException("unable PDO connection.");
        }

        return $this->connection;
     }


     /**
      * @return PDO
      * @throws ConnectionLogicException
     */
     public function getConnection(): PDO
     {
         return $this->getPdo();
     }


    /**
      * @return string|null
     */
     protected function getUsername(): ?string
     {
         return $this->config['username'];
     }



     /**
       * @return string|null
     */
     protected function getPassword(): ?string
     {
         return $this->config['password'];
     }


     /**
      * Generate DSN
      *
      * @param array|ArrayAccess $config
      * @return string
     */
     protected function makeDSN($config): string
     {
        return sprintf('%s:host=%s;port=%s;dbname=%s;',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database']
        );
     }




    /**
     * @return void
     * @throws ConnectionLogicException
     */
    public function beginTransaction()
    {
        $this->getPdo()->beginTransaction();
    }



    /**
     * @return void
     * @throws ConnectionLogicException
     */
    public function commit()
    {
        $this->getPdo()->commit();
    }



    /**
     * @return void
     * @throws ConnectionLogicException
    */
    public function rollback()
    {
        $this->getPdo()->rollBack();
    }






    /**
     * @return int
     * @throws ConnectionLogicException|StatementException
    */
    public function lastInsertId(): int
    {
        return $this->getPdo()->lastInsertId();
    }



    /**
     * @param string $sql
     * @return false|int
     * @throws ConnectionLogicException
     */
    public function exec($sql)
    {
        return $this->getPdo()->exec($sql);
    }




    /**
     * Example : prefix = laventure_ , table = users => tableName = laventure_users
     *
     * @param string $table
     * @return string
    */
    protected function getTableRealName(string $table): string
    {
        return $this->config['prefix'] . $table;
    }



    /**
     * @throws ConnectionLogicException
    */
    public function makeConnection($config): PDO
    {
        return Connection::make($config);
    }
}