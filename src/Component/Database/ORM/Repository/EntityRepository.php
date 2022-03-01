<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\EntityManager;


/**
 * @EntityRepository
*/
class EntityRepository implements EntityRepositoryInterface
{


      /**
       * @var EntityManager
      */
      protected $em;




      /**
       * @var string
      */
      protected $entityClass;




      /**
       * @var string
      */
      private $table;




      /**
       * @var string
      */
      private $alias;




      /**
       * @param EntityManager $em
       * @param string $entityClass
      */
      public function __construct(EntityManager $em, string $entityClass)
      {
            $this->registerClassMap($em, $entityClass);
      }



      /**
       * @param string $alias
       * @return Select
      */
      public function createQueryBuilder(string $alias): Select
      {
           $this->em->withTableAlias($alias);

           return $this->em->createQueryBuilder()
                           ->select(["*"])
                           ->from($this->table, $alias);

      }



      /**
       * @param array $criteria
       * @return false|mixed|object|void
      */
      public function findOneBy(array $criteria)
      {
           return (function () use ($criteria) {

               $qb = $this->em->getPersistence()->findQuery($criteria);

               return $qb->getQuery()->getOneOrNullResult();

           })();
      }




       /**
        * @return array
       */
       public function findAll(): array
       {
          return (function () {
              return $this->createQueryBuilder($this->alias)
                          ->getQuery()
                          ->getResult();
          })();
      }


      /**
       * @param array $criteria
       * @return array
       */
      public function findBy(array $criteria): array
      {
          return (function () use ($criteria) {

              $qb = $this->em->getPersistence()->findQuery($criteria);

              return $qb->getQuery()->getResult();

          })();
      }



     /**
      * @param EntityManager $em
      * @param $entityClass
      * @return void
     */
     private function registerClassMap(EntityManager $em, $entityClass)
     {
         $em->withClassMap($entityClass);
         $this->table = $em->getTableName();
         $this->alias = $em->getTableAlias();
         $this->entityClass = $entityClass;
         $this->em = $em;
     }
}