<?php
namespace Laventure\Component\Database\ORM;


use Closure;
use Exception;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\QueryFactory;
use Laventure\Component\Database\ORM\Common\DataMapper;
use Laventure\Component\Database\ORM\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\Exception\EntityManagerException;
use Laventure\Component\Database\ORM\Query\QueryBuilder;
use Laventure\Component\Database\ORM\Repository\Common\Persistence;
use Laventure\Component\Database\ORM\Repository\EntityRepository;
use Laventure\Component\Database\ORM\Repository\PersistenceFactory;
use Laventure\Component\Database\Utils\Str;


/**
 * @EntityManager
*/
abstract class EntityManager implements EntityManagerInterface
{

     use DataMapper;



     /**
      * @var Persistence
     */
     public $persistence;




     /**
      * @var ConnectionInterface
     */
     public $connection;





     /**
      * @var string
     */
     protected $classMap;




     /**
      * @var string
     */
     protected $identity = 'id';




     /**
      * @var string
     */
     protected $table;




     /**
      * @var string
     */
     protected $alias;






     /**
      * @var QueryInterface
     */
     protected $query;




     /**
      * @var array
     */
     protected $updates = [];





     /**
      * @var array
     */
     protected $persists = [];





     /**
      * @var array
     */
     protected $removes = [];






     /**
      * @param ConnectionInterface $connection
      * @throws Exception
     */
     public function __construct(ConnectionInterface $connection)
     {
          $this->connection   = $connection;
          $this->persistence  = $this->makePersistence($connection);
     }




     /**
      * @return ConnectionInterface
     */
     public function getConnectionManager(): ConnectionInterface
     {
         return $this->connection;
     }






     /**
      * @return mixed
     */
     public function getConnection()
     {
          return $this->connection->getConnection();
     }





     /**
      * @return string
     */
     public function getClassMap(): string
     {
         return $this->classMap;
     }




     /**
      * @return Persistence
     */
     public function getPersistence(): Persistence
     {
         return $this->persistence;
     }




     /**
      * @param $class
      * @param string|null $table
      * @return self
     */
     public function registerClass($class, string $table = null): self
     {
          $classMap = \is_object($class) ? get_class($class) : $class;

          $this->classMap = $classMap;

          $table = $table ?? $this->createTableName($classMap);

          $this->table($table);

          return $this;
    }




    /**
     * @param string $table
     * @param string|null $alias
     * @return $this
    */
    public function table(string $table, string $alias = null): self
    {
        $this->table = $table;
        $alias = $alias ?? $this->createTableAlias($table);

        $this->alias($alias);

        return $this;
    }





    /**
     * @param $alias
     * @return $this
    */
    public function alias($alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    


    /**
     * @param object $concrete
     * @return array
    */
    public function getClassMetadata(object $concrete): array
    {
        $this->registerClass(get_class($concrete));

        return $this->map($concrete);
    }




    /**
     * @return string
    */
    public function getTable(): string
    {
        if ($this->table) {
            return $this->table;
        }

        return $this->table = $this->createTableName($this->classMap);
    }




    /**
     * @return string
    */
    public function getAlias(): string
    {
        if ($this->alias) {
            return $this->alias;
        }

        return $this->alias = $this->createTableAlias($this->getTable());
    }





    /**
     * @param string $sql
     * @return false|int
    */
    public function exec($sql)
    {
        return $this->connection->exec((string)$sql);
    }




    /**
     * Create a native query
     *
     * @param $sql
     * @param array $params
     * @return QueryInterface
     * @throws Exception
    */
    public function createNativeQuery($sql, array $params = []): QueryInterface
    {
         $query = $this->createQuery();

         $query->prepare($sql, $params);

         return $this->query = $query;
    }




    /**
     * @return QueryInterface
    */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }





    /**
     * @return QueryInterface
    */
    public function createQuery(): QueryInterface
    {
        return QueryFactory::make($this->connection, $this->classMap);
    }





    /**
     * Create a query builder
     *
     * @return QueryBuilder
    */
    public function createQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder($this);
    }




    /**
     * @return EntityRepository
    */
    public function createRepository(): EntityRepository
    {
        return new EntityRepository($this, $this->getClassMap());
    }




    /**
     * @param $object
     * @return $this
    */
    public function persist($object): self
    {
        $this->preUpdate($object);
        $this->prePersist($object);

        $data = $this->getClassMetadata($object);

        $id = $data[$this->identity] ?? $this->persistence->generateId();

        $this->persists[$id] = $data;

        return $this;
    }





    /**
     * @param $object
     * @return $this
    */
    public function remove($object): self
    {
          $this->preRemove($object);

          $data = $this->getClassMetadata($object);

          if (! empty($data[$this->identity])) {

              $id = $data[$this->identity];

              $this->removes[$id] = $id;
          }

          return $this;
    }




    /**
     * @inheritDoc
     */
    public function transaction(Closure $closure)
    {
        (function () use ($closure) {

            try {

                $this->beginTransaction();

                $closure($this);

                $this->commit();

            } catch (Exception $e) {

                $this->rollback();

                throw new EntityManagerException($e->getMessage(), $e->getCode());
            }

        })();
    }







    /**
     * @return void
    */
    public function flush()
    {
        $this->preFlush();

        $this->transaction(function () {
            $this->flushProcess();
        });
    }





    /**
     * @return void
    */
    protected function preFlush()
    {
        $this->persistUpdates();
    }




    /**
     * @inheritDoc
    */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }




    /**
     * @inheritDoc
    */
    public function commit()
    {
        $this->connection->commit();
    }




    /**
     * @inheritDoc
    */
    public function rollback()
    {
        $this->connection->rollback();
    }



    /**
     * @return int
    */
    public function lastInsertId(): int
    {
        return $this->connection->lastInsertId();
    }





    /**
     * @param object $object
     * @return void
    */
    public function updates(object $object)
    {
        $this->updates[] = $object;
    }



    /**
     * @return void
    */
    public function removeUpdates()
    {
        $this->updates = [];
    }



    /**
     * @return array
    */
    public function getUpdates(): array
    {
        return $this->updates;
    }





    /**
     * @return array
    */
    public function getRemoves(): array
    {
        return $this->removes;
    }





    /**
     * @return void
    */
    protected function persistUpdates()
    {
        foreach ($this->updates as $object) {
            $this->persist($object);
        }
    }




    /**
     * @param object $object
     * @return void
    */
    protected function prePersist(object $object)
    {
        if (method_exists($object, 'prePersist')) {
            $object->prePersist();
        }
    }





    /**
     * @param object $object
     * @return void
    */
    protected function preUpdate(object $object)
    {
        if (method_exists($object, 'preUpdate')) {
            $object->preUpdate();
        }
    }




    /**
     * @param object $object
     * @return void
    */
    protected function preRemove(object $object)
    {
        if (method_exists($object, 'preRemove')) {
            $object->preRemove();
        }
    }





    /**
     * @param string $entityClass
     * @return string
    */
    public function createTableName(string $entityClass): string
    {
        $shortName = $this->getShortName($entityClass);

        return mb_strtolower(trim($shortName, 's')) . 's';
    }




    /**
     * @param string $tableName
     * @return string
    */
    public function createTableAlias(string $tableName): string
    {
        return Str::substr($tableName);
    }



    /**
     * @param array $attributes
     * @param $id
     * @return mixed
    */
    public function update(array $attributes, $id)
    {
        return $this->persistence->update($attributes, $id);
    }





    /**
     * @param array $attributes
     * @return void
    */
    public function insert(array $attributes)
    {
        return $this->persistence->insert($attributes);
    }




    /**
     * @param int $id
     * @return mixed
    */
    public function delete(int $id)
    {
        return $this->persistence->delete($id);
    }






    /**
     * @param ConnectionInterface $connection
     * @return Persistence
     * @throws Exception
    */
    protected function makePersistence(ConnectionInterface $connection): Persistence
    {
        return (new PersistenceFactory($this))->make($connection);
    }





    /**
     * @param $name
     * @return string
    */
    protected function getShortName($name): string
    {
        return  (new \ReflectionClass($name))->getShortName();
    }




    /**
     * @return void
    */
    protected function flushProcess()
    {
         $this->persistProcess();
         $this->removeProcess();
    }




    /**
     * @return void
    */
    protected function persistProcess()
    {
        if ($this->persists) {
            foreach ($this->persists as $attributes) {
                $this->persistence->persist($attributes);
            }
        }
    }



    /**
     * @return void
    */
    protected function removeProcess()
    {
        if ($this->removes) {
            foreach ($this->removes as $id) {
                $this->persistence->delete($id);
            }
        }
    }



    /**
     * Get repository from storage entities
     *
     * @return EntityRepository
     * @inheritDoc
    */
    abstract public function getRepository($name): EntityRepository;
}