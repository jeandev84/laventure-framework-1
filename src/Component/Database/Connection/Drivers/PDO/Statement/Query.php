<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Statement;



use Laventure\Component\Database\Connection\Contract\QueryEntityMapperInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Exception\StatementException;
use PDO;
use PDOStatement;



/**
 * @Query
*/
class Query implements QueryInterface, QueryEntityMapperInterface
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
    public function prepare(string $sql, array $params = []): self
    {
        $this->statement = $this->pdo->prepare($sql);
        $this->sql       = $sql;
        $this->params    = $params;

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
     * Example:
     *
     *  bind(':name', 'John')
     *
     * @param string $param
     * @param $value
     * @param int $type
     * @return $this
    */
    public function bind(string $param, $value, int $type = 0): self
    {
        if ($type === 0) {
            
            $typeName = strtolower(gettype($type));

            switch ($typeName) {
                case 'integer':
                    $type = PDO::PARAM_INT;
                break;
                case 'boolean':
                    $type = PDO::PARAM_BOOL;
                break;
                case 'null':
                    $type = PDO::PARAM_NULL;
                break;
                default:
                    $type = PDO::PARAM_STR;
                break;
            }
        }

        $this->bindValues[] = [$param, $value, $type];

        return $this;
    }



    /**
     * @inheritDoc
     * @return bool
     * @throws StatementException
    */
    public function execute(): bool
    {
        try {

            if ($this->bindValues) {
                 $this->executeQueryBindParams();
            }else {
                if ($this->statement->execute($this->params)) {
                    $this->addToCache($this->sql, $this->params);
                }
            }

            return true;

        } catch (\PDOException $e) {

            $message = sprintf('SQL : %s, Message : %s', $this->sql, $e->getMessage());

            throw new StatementException($message);
        }
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



    /**
     * @inheritDoc
    */
    public function getSingleScalarResult()
    {
        return $this->statement->rowCount();
    }



    /**
     * @inheritDoc
    */
    public function errors()
    {
        return $this->statement->errorInfo();
    }


    /**
     * @return array
    */
    private function populateBindValues(): array
    {
            $params = [];

            foreach ($this->bindValues as $bindValue) {
                list($param, $value, $type) = $bindValue;
                $this->statement->bindValue($param, $value, $type);
                $params[$param] = $value;
            }

            return $params;
    }





    /**
     * @return void
    */
    private function executeQueryBindParams()
    {
        $params = $this->populateBindValues();

        if ($this->statement->execute()) {
            $this->addToCache($this->sql, $params);
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

}