<?php
namespace Laventure\Component\Database\ORM\Common;


use Closure;
use Exception;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\QueryFactory;
use Laventure\Component\Database\ORM\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\Contract\EntityRepositoryInterface;
use Laventure\Component\Database\ORM\Exception\EntityManagerException;
use Laventure\Component\Database\ORM\Query\QueryBuilder;
use Laventure\Component\Database\ORM\Repository\EntityRepository;
use Laventure\Component\Database\ORM\Repository\Persistence;
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
    protected $persistence;




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
    protected $tableName;




    /**
     * @var string
     */
    protected $tableAlias;






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
        $this->connection  = $connection;
        $this->persistence = $this->makePersistence($connection);
    }




    /**
     * @return Persistence
    */
    public function getPersistence(): Persistence
    {
        return $this->persistence;
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
     * @param $concrete
     * @param string|null $table
     * @return self
    */
    public function withClass($concrete, string $table = null): self
    {
        $classMap = \is_object($concrete) ? get_class($concrete) : $concrete;

        $this->classMap  = $classMap;

        $table = $table ?? $this->createTableName($classMap);

        $this->withTable($table);

        return $this;
    }





    /**
     * @param string $tableName
     * @return $this
     */
    public function withTable(string $tableName): self
    {
        $this->tableName  = $tableName;
        $this->tableAlias = $this->createTableAlias($tableName);

        return $this;
    }





    /**
     * @param $tableAlias
     * @return $this
     */
    public function withTableAlias($tableAlias): self
    {
        $this->tableAlias = $tableAlias;

        return $this;
    }




    /**
     * @param object $concrete
     * @return array
     */
    public function getClassMetadata(object $concrete): array
    {
        $this->withClass(get_class($concrete));

        return $this->map($concrete);
    }




    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }




    /**
     * @return string
     */
    public function getTableAlias(): string
    {
        return $this->tableAlias;
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

        $query->prepare($sql);
        $query->withParams($params);

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
     * @return void
    */
    public function persist($object)
    {
        $this->preUpdate($object);
        $this->prePersist($object);

        $data = $this->getClassMetadata($object);

        $this->persistence->persist($data);

        $this->persists[] = $object;

    }



    /**
     * @param $object
     * @return void
     */
    public function remove($object)
    {
          $this->preRemove($object);

          $data = $this->getClassMetadata($object);

          if (! empty($data['id'])) {
              $this->persistence->remove((int)$data['id']);
          }

          $this->removes[] = $object;
    }




    /**
     * @return array
    */
    public function getRemoves(): array
    {
        return $this->removes;
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
            $this->persistence->flush();
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
     * @param ConnectionInterface $connection
     * @return Persistence
     * @throws Exception
     */
    protected function makePersistence(ConnectionInterface $connection): Persistence
    {
        $persistence = (new PersistenceFactory($this))->make($connection);

        return new Persistence($persistence);
    }





    /**
     * @param string $entityClass
     * @return string
    */
    public function createTableName(string $entityClass): string
    {
        $shortName = $this->getShortName($entityClass);

        return mb_strtolower(trim($shortName, 's')). 's';
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
     * @param $name
     * @return string
    */
    protected function getShortName($name): string
    {
        return  (new \ReflectionClass($name))->getShortName();
    }



    /**
     * Get repository from storage entities
     *
     * @return EntityRepository
     * @inheritDoc
    */
    abstract public function getRepository($name): EntityRepository;
}