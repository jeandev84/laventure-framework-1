<?php
namespace Laventure\Component\Database;


use Exception;
use Laventure\Component\Database\Connection\ConnectionFactory;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Statement\Query;
use Laventure\Component\Database\Connection\Exception\ConnectionException;
use Laventure\Component\Database\Managers\DatabaseManager;
use Laventure\Component\Database\Managers\Exception\DatabaseManagerException;
use Laventure\Component\Database\ORM\Common\EntityManager;
use Laventure\Component\Database\ORM\Query\QueryBuilder;
use Laventure\Component\Database\ORM\Query\QueryBuilderFactory;
use Laventure\Component\Database\Schema\Schema;
use LogicException;
use PDO;


/**
 * @Manager
*/
class Manager extends DatabaseManager
{

    /**
     * @var Manager
     */
    protected static $instance;




    /**
     * @var EntityManager
     */
    protected $em;




    /**
     * @param array $config
     * @param string $connection
     * @return void
     */
    public function addConnection(array $config, string $connection)
    {
        $this->connect($connection, $config);
    }



    /**
     * @return void
    */
    public function bootAsGlobal()
    {
         if (! static::$instance) {
             static::$instance = $this;
         }
    }


    /**
     * @return PdoConnectionInterface
     * @throws ConnectionException
     * @throws LogicException
     * @throws Exception
    */
    public function getConnection(): PdoConnectionInterface
    {
        $connection = $this->connection();

        if (! $connection instanceof PdoConnectionInterface) {
            throw new Exception("Manager ". get_class() . " use PDO connection.");
        }

        return $connection;
    }



    /**
     * @return PDO
     * @throws ConnectionException
     * @throws LogicException
    */
    public function pdo(): PDO
    {
        return $this->getConnection()->getPdo();
    }


    /**
     * @param string|null $name
     * @return PdoConnectionInterface
     * @throws ConnectionException
    */
    public function pdoConnection(string $name = null): PdoConnectionInterface
    {
        $connection = $this->connection($name);

        return ConnectionFactory::pdoConnection($connection);
    }



    /**
     * @param $connection
     * @return bool
    */
    protected function isPdoConnection($connection): bool
    {
        return $connection instanceof PdoConnectionInterface;
    }





    /**
     * @return Manager
     *
     * @throws DatabaseManagerException
    */
    public static function instance(): Manager
    {
        if (static::$instance) {
            return static::$instance;
        }

        throw new DatabaseManagerException('Connection to database is not booted!');
    }




    /**
     * @param EntityManager $em
    */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }




    /**
     * Get current entity manager
     *
     * @return EntityManager
    */
    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }




    /**
     * Get entity manager compact method
     *
     * @return EntityManager
    */
    public function em(): EntityManager
    {
        return $this->em;
    }




    /**
     * @throws ConnectionException
     * @throws LogicException
    */
    public function schema(): Schema
    {
        return new Schema($this->getConnection());
    }





    /**
     * Create a native query
     *
     *
     * @param string $sql
     * @param array $params
     * @return Query
     * @throws ConnectionException
     * @throws LogicException
    */
    public function query(string $sql, array $params): Query
    {
        return $this->getConnection()->query($sql, $params);
    }




    /**
     * Example
     *  DB:table('products')->insert(['title' => 'product 1', 'price' => 200$]);
     *
     *  DB:table('products')->update(['title' => 'product 1', 'price' => 230$])
     *                      ->where('id = :id')
     *                      ->setParameter('id', 3)
     *                      ->execute();
     * @param string $name
     * @return QueryBuilder
     * @throws Exception
    */
    public function table(string $name): QueryBuilder
    {
        $this->em->withTable($name);

        return QueryBuilderFactory::make($this->em);
    }




    // todo implements
    public function createDatabase() {}
    public function dropDatabase() {}
    public function backupDatabase() {}
    public function exportDatabase() {}
    public function importDatabase() {}
}