<?php
namespace Laventure\Foundation\Database\ORM\Common;

use Exception;
use Laventure\Component\Database\Connection\Exception\StatementException;
use Laventure\Component\Database\Managers\Exception\DatabaseManagerException;
use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Query\QueryBuilder;
use Laventure\Component\Database\ORM\Repository\ActiveRecord;
use Laventure\Foundation\Database\Laventure\Manager;
use Laventure\Foundation\Database\ORM\Model;


/**
 * @AbstractModel
*/
class AbstractModel extends ActiveRecord
{


     use ModelTrait;




    /**
     * @var array
     */
    protected $selects = [];



    /**
     * @var array
     */
    protected $wheres = [];




    /**
     * @var string
     */
    protected $limit;




    /**
     * @var Model
     */
    public static $instance;



    /**
     * @var static
     */
    public static $qb;



    /**
     * @throws DatabaseManagerException
    */
    public function __construct()
    {
        parent::__construct(Manager::instance());
    }




    /**
     * @return Model
     */
    private static function make(): Model
    {
        if (! static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }




    /**
     * @return string
     */
    private static function getTableName(): string
    {
        return (new static())->getTable();
    }




    /**
     * @return QueryBuilder
     */
    private static function createQueryBuilder(): QueryBuilder
    {
        return (new static())->em->createQueryBuilder();
    }



    /**
     * @throws StatementException
    */
    public static function find($id)
    {
        return static::make()->findOne($id);
    }




    /**
     * @param ...$args
     * @return Select
    */
    public static function select(...$args): Select
    {
        $qb = static::createQueryBuilder();

        $model = static::make();
        $model->selects[] = $args;

        return self::$qb = $qb->select($args[0])->from(static::getTableName());
    }





    /**
     * @param string $column
     * @param mixed $value
     * @param string $operator
     * @return self
     */
    public static function where(string $column, $value, string $operator = '='): self
    {
        $model = static::make();

        $model->wheres[] = [$column, $value, $operator];

        return $model;
    }




    /**
     * @return array
     */
    public function get(): array
    {
        return (function () {

            $qb = static::select(["*"]);

            $qb = $this->populateWheres($qb);

            return $qb->getQuery()->getResult();

        })();
    }


    /**
     * @return false|mixed|object|void
     */
    public function one()
    {
        return (function () {

            $qb = static::select(["*"]);

            $qb = $this->populateWheres($qb);

            return $qb->getQuery()->getOneOrNullResult();
        })();
    }


    /**
     * @return array
     */
    public static function all(): array
    {
        if (self::make()->wheres) {
            return self::make()->get();
        }

        return static::make()->findAll();
    }


    /**
     * @param null $id
     * @return mixed
    */
    public static function remove($id = null)
    {
        $model = static::make(); // todo make via clone $model = clone new static();

        if ($id) {
            return $model->delete($id);
        }

        if($wheres = $model->wheres) {
            return $model->delete($id, $wheres);
        }

        return false;
    }




    /**
     * @return void
     * @throws Exception
     */
    public function save()
    {
        $columns = $this->getTableColumns();

        $attributes = [];

        foreach ($columns as $column) {

            if (! empty($this->fillable)) {
                if (\in_array($column, $this->fillable)) {
                    $attributes[$column] = $this->{$column};
                }
            }else {
                $attributes[$column] =  $this->{$column};
            }
        }

        if (! empty($this->guarded)) {
            foreach ($this->guarded as $guarded) {
                if (isset($attributes[$guarded])) {
                    unset($attributes[$guarded]);
                }
            }
        }


        // dd($attributes);

        $id = (int) $this->getAttribute($this->primaryKey);

        if ($id) {
            $this->update($attributes, [$this->primaryKey => $id])->execute();
        }else{
            $this->insert($attributes);
            $this->setAttribute($this->primaryKey, $this->em->lastInsertId());
        }
    }





    /**
     * @return array
     * @throws Exception
     */
    private function getTableColumns(): array
    {
        // $sql = sprintf('SHOW COLUMNS FROM %s;', $this->getTable());
        // $sql = sprintf('select * from information_schema.columns where table_name = %s', $this->getTable());
        // $sql = sprintf('describe %s', $this->getTable());

        $this->em->exec($this->createSQLFunctionShowColumns());
        $fields =  $this->em->createNativeQuery($this->sqlShowColumnsMoreSure())->getResult();

        // dd($fields);

        // todo use array_filter() for implementations
        $columns = [];

        foreach ($fields as $field) {
            $columns[] = $field->attributes['column_name'];
        }

        // dd($columns);

        return $columns;
    }



    protected function createSQLFunctionShowColumns()
    {
        return "create or replace function describe_table(tbl_name text) returns table(column_name   
                    varchar, data_type varchar,character_maximum_length int) as $$
                    select column_name, data_type, character_maximum_length
                    from INFORMATION_SCHEMA.COLUMNS where table_name = $1;
                    $$
                    language 'sql';
            ";
    }


    protected function sqlShowColumnsMoreSure()
    {
        return sprintf("select  *  from describe_table('%s')", $this->getTable());
    }


    protected function sqlShowColumnsNotSure(): string
    {
        return sprintf("
            SELECT
               table_schema || '.' || table_name as show_tables
            FROM
               information_schema.tables
            WHERE
               table_type = '%s'
            AND
            table_schema NOT IN ('pg_catalog', 'information_sc')
          ", 'BASE TABLE');
    }
}