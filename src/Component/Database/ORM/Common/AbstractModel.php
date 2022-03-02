<?php
namespace Laventure\Component\Database\ORM\Common;

use Exception;
use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Model;
use Laventure\Component\Database\ORM\Query\QueryBuilder;
use Laventure\Component\Database\ORM\Repository\ActiveRecord;


/**
 * @AbstractModel
*/
abstract class AbstractModel extends ActiveRecord
{


     use ModelTrait;




     /**
      * @var array
     */
     protected $wheres = [];





     /**
      * @var Model
     */
     public static $instance;




    /**
     * @return Model
    */
    private static function instance(): Model
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
        return self::instance()->getTable();
    }




    /**
     * Create query builder
     *
     * @return QueryBuilder
    */
    private static function createQB(): QueryBuilder
    {
        return static::instance()->entityManager->createQueryBuilder();
    }




    /**
     * @param $id
     * @return false|mixed|object|void
    */
    public static function find($id)
    {
        return static::instance()->findOne($id);
    }




    /**
     * @param array $criteria
     * @return array
    */
    public static function findBy(array $criteria): array
    {
        return static::instance()->repository->findBy($criteria);
    }





    /**
     * @param ...$args
     * @return Select
    */
    public static function select(...$args): Select
    {
        $qb = static::createQB();

        return $qb->select($args[0])->from(static::getTableName());
    }





    /**
     * @param string $column
     * @param mixed $value
     * @param string $operator
     * @return self
    */
    public static function where(string $column, $value, string $operator = '='): self
    {
        $model = static::instance();

        $model->wheres[] = [$column, $value, $operator];

        return $model;
    }




    /**
     * @return array
     */
    public function get(): array
    {
        return (function () {

            return $this->repository->findBy($this->wheres);

        })();
    }




    /**
     * @return false|mixed|object|void
    */
    public function one()
    {
        return (function () {

            return $this->repository->findOneBy($this->wheres);

        })();
    }





    /**
     * @return array
    */
    public static function all(): array
    {
        return static::instance()->findAll();
    }




    /**
     * Product::remove(3);
     *
     * Product::where('tile', 'Book')->remove();
     *
     * @param null $id
     * @return false
    */
    public static function remove($id = null): bool
    {
        $model = static::instance();

        if ($id) {
            $model->delete($id);
            return true;
        }

        if($wheres = $model->wheres) {
            $model->persistence->deleteWheres($wheres);
            return true;
        }

        return false;
    }




    /**
     * @return void
     * @throws Exception
    */
    public function save()
    {
        $columns = $this->connection->showTableColumns($this->getTable());

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
            $this->update($attributes, $id);
        }else{
            $this->insert($attributes);
            $this->setAttribute($this->primaryKey, $this->entityManager->lastInsertId());
        }
    }
}