<?php
namespace Laventure\Component\Database\ORM\Query;


use Laventure\Component\Database\ORM\Builder\Delete;
use Laventure\Component\Database\ORM\Builder\Insert;
use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Builder\Update;
use Laventure\Component\Database\ORM\EntityManager;


/**
 * QueryBuilder use PDO
 *
 * @QueryBuilder
*/
class QueryBuilder
{


      /**
       * @var EntityManager
      */
      protected $em;



      /**
       * @var string
      */
      protected $table;



      /**
       * @var string
      */
      protected $alias;



      /**
       * @param EntityManager $em
      */
      public function __construct(EntityManager $em)
      {
           $this->em    = $em;
           $this->table = $em->getTable();
           $this->alias = $em->getAlias();
      }





      /**
        * @param array $selects
       * @return Select
      */
      public function select(array $selects): Select
      {
          $qb = new Select($selects);

          $qb->setEntityManager($this->em);

          return $qb;
      }




      /**
       * @param array $attributes
       * @return mixed
      */
      public function insert(array $attributes)
      {
          // todo check a correct insert or update query (pdo/mysqli)
          return (function () use ($attributes) {

              $qb = new Insert($attributes, $this->table);

              $qb->setEntityManager($this->em);

              $qb->execute();

          })();
      }



      /**
       * @param array $attributes
       * @return Update
      */
      public function update(array $attributes): Update
      {
          return (function () use ($attributes) {

              $qb = new Update($attributes, $this->table);

              $qb->setEntityManager($this->em);

              return $qb;

          })();
      }




      /**
       * @return Delete
      */
      public function delete(): Delete
      {
          return (function () {

              $qb = new Delete($this->table);

              $qb->setEntityManager($this->em);

              return $qb;

          })();
      }

}