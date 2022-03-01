<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\Connection\Exception\StatementException;
use Laventure\Component\Database\Manager;
use Laventure\Component\Database\ORM\Builder\Delete;
use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Builder\Update;
use Laventure\Component\Database\ORM\Contract\ActiveRecordInterface;
use Laventure\Component\Database\ORM\EntityManager;


/**
 * @ActiveRecord
*/
abstract class ActiveRecord implements ActiveRecordInterface
{


       /**
        * @var Manager
       */
       protected $db;



       /**
        * @var EntityManager
       */
       protected $em;




       /**
        * @var
       */
       protected $connection;




       /**
        * @var
       */
       protected $table;





       /**
        * @var string
       */
       protected $primaryKey = 'id';




       /**
        * @param Manager $db
       */
       public function __construct(Manager $db)
       {
             $em = $db->getEntityManager();
             $em->withClassMap(get_called_class(), $this->getTable());
             $this->db = $db;
             $this->em = $em;
             $this->connection = $em->getConnectionManager();
       }



       /**
        * @param $id
        * @return false|mixed|object|void
       */
       public function findOne($id)
       {
           $qb = $this->em->createQueryBuilder();

           return $qb->select(["*"])->from($this->table)
                                    ->where($this->getPrimaryKeyCondition())
                                    ->setParameter("{$this->primaryKey}", $id)
                                    ->getQuery()->getOneOrNullResult();
       }



       /**
        * @return array
       */
       public function findAll(): array
       {
          return (function () {

              $qb = $this->em->createQueryBuilder();

              return $qb->select(["*"])
                        ->from($this->table)
                        ->getQuery()
                        ->getResult();
          })();
       }




       /**
        * @param array $attributes
        * @return void
       */
       public function insert(array $attributes)
       {
           $qb = $this->em->createQueryBuilder();

            return $qb->insert($attributes);
       }




       /**
        * @param array $attributes
        * @param array $wheres
        * @return Update
       */
       public function update(array $attributes, array $wheres): Update
       {
            return (function () use ($attributes, $wheres) {

                $qb = $this->em->createQueryBuilder()
                               ->update($attributes);

                foreach (array_keys($wheres) as $column) {
                    $qb->where("$column = :{$column}");
                }

                $qb->setParameters($wheres);

                return $qb;

           })();
     }



    /**
     * @param $id
     * @param array $criteria
     * @return Delete|mixed|void
    */
    public function delete($id, array $criteria = [])
    {
         return (function () use ($id, $criteria) {
             $qb = $this->em->createQueryBuilder()
                            ->delete()
                            ->where($this-> getPrimaryKeyCondition())
                            ->setParameter("{$this->primaryKey}", $id);


             if ($criteria) {

                 foreach (array_keys($criteria) as $column) {
                     $qb->where("$column = :{$column}");
                 }

                 $qb->setParameters($criteria);
             }

             return $qb->execute();

         })();
    }




     /**
      * @param Select $qb
      * @return Select
     */
     protected function populateWheres(Select $qb): Select
     {
         if ($this->wheres) {
             foreach ($this->wheres as $where) {
                 list($column, $value, $operator) = $where;
                 $qb->andWhere("$column $operator :$column")
                     ->setParameter($column, $value);
             }
         }

         return $qb;
     }




      /**
       * @return string
      */
      protected function getTable()
      {
           return $this->table;
      }



      /**
       * @return string
      */
      protected function getPrimaryKeyCondition(): string
      {
          return sprintf('%s = :%s', $this->primaryKey, $this->primaryKey);
      }
}