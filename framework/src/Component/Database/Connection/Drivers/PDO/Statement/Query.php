<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Statement;



use Laventure\Component\Database\Connection\Contract\QueryClassMapInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Exception\StatementException;
use PDO;
use PDOStatement;



/**
 * @Query
*/
class Query implements QueryInterface, QueryClassMapInterface
{

    /**
     * @var PDO $pdo
     */
    protected $pdo;



    /**
     * @var string
     */
    protected $sql = '';



    /**
     * @var array
     */
    protected $params = [];



    /**
     * @var array
     */
    protected $bindValues = [];




    /**
     * @var PDOStatement
     */
    protected $statement;




    /**
     * @var int
     */
    protected $fetchMode = PDO::FETCH_OBJ;




    /**
     * @var string
     */
    protected $entityClass = \stdClass::class;




    /**
     * @var array
     */
    protected $cache = [];



    /**
     * @param PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }



    /**
     * @inheritDoc
    */
    public function prepare(string $sql): self
    {
        $this->statement = $this->pdo->prepare($sql);
        $this->sql       = $sql;

        return $this;
    }



    /**
     * @param array $params
     * @return $this
     */
    public function withParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }



    /**
     * @param int $mode
     * @return self
     */
    public function withFetchMode(int $mode): self
    {
        $this->fetchMode = $mode;

        return $this;
    }



    /**
     * @param string|null $entityClass
     * @return $this
     */
    public function withEntity(string $entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }



    /**
     * @param string $param
     * @param $value
     * @param int $type
     * @return $this
    */
    public function bindValue(string $param, $value, int $type = 0): self
    {
        $this->bindValues[] = [$param, $value, $type];

        return $this;
    }



    /**
     * @inheritDoc
     * @throws StatementException
    */
    public function execute()
    {
        try {

            if ($this->bindValues) {

                $params = [];

                foreach ($this->bindValues as $bindValue) {
                    list($name, $value, $type) = $bindValue;
                    $this->statement->bindValue(':', $value, $type);
                    $params[$name] = $value;
                }

                if ($this->statement->execute()) {
                    $this->addToCache($this->sql, $params);
                }

            }

            if ($this->statement->execute($this->params)) {
                $this->addToCache($this->sql, $this->params);
            }

        } catch (\PDOException $e) {

            $message = sprintf('SQL : %s, Message : %s', $this->sql, $e->getMessage());

            throw new StatementException($message);
        }
    }




    /**
     * @param string $sql
     * @param array $params
     * @return void
     */
    public function addToCache(string $sql, array $params)
    {
        $this->cache[$sql] = $params;
    }


    /**
     * @inheritDoc
     * @throws StatementException
     */
    public function getResult()
    {
        $this->execute();

        if ($this->entityClass) {
            $this->statement->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);
            return $this->statement->fetchAll();
        }

        return $this->statement->fetchAll($this->fetchMode);
    }


    /**
     * @inheritDoc
     * @throws StatementException
     */
    public function getOneOrNullResult()
    {
        $this->execute();

        if($this->entityClass) {
            return $this->statement->fetchObject($this->entityClass);
        }

        return $this->statement->fetch($this->fetchMode);
    }



    /**
     * @inheritDoc
     * @throws StatementException
     */
    public function getArrayColumns()
    {
        $this->execute();

        return $this->statement->fetchAll(PDO::FETCH_COLUMN);
    }



    /**
     * @inheritDoc
     * @throws StatementException
    */
    public function getFirstResult()
    {
        return  $this->getResult()[0] ?? null;
    }
}