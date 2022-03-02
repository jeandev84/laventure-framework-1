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
      private $entityClass;




      /**
       * @param EntityManager $em
       * @param string $entityClass
      */
      public function __construct(EntityManager $em, string $entityClass)
      {
             $this->em = $em;
             $this->entityClass = $entityClass;
      }



      /**
       * @param string $alias
       * @return Select
      */
      public function createQueryBuilder($alias): Select
      {
           $this->em->registerClass($this->entityClass);

           $qb = $this->em->createQueryBuilder()->select(["*"]);

           return $qb->from($this->getTableName(), $alias);
      }




      /**
       * @param string|null $alias
       * @return Select
      */
      public function createSelectQuery(string $alias = null): Select
      {
           $this->em->registerClass($this->entityClass);

           if ($alias) {
               $this->em->alias($alias);
           }

           return $this->em->createQueryBuilder()->select(["*"]);
      }




      /**
       * @param array $criteria
       * @return false|mixed|object|void
      */
      public function findOneBy(array $criteria)
      {
           return (function () use ($criteria) {

               $this->em->registerClass($this->entityClass);

               $qb = $this->em->persistence->findQuery($criteria);

               return $qb->getQuery()->getOneOrNullResult();

           })();
      }




       /**
        * @return array
       */
       public function findAll(): array
       {
          return (function () {
              return $this->createQueryBuilder(null)
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

              $this->em->registerClass($this->entityClass);

              $qb = $this->em->persistence->findQuery($criteria);

              return $qb->getQuery()->getResult();

          })();
      }




      /**
       * @return string
      */
      private function getTableName(): string
      {
          return $this->em->createTableName($this->entityClass);
      }




      /**
       * @return string
      */
      private function getTableAlias(): string
      {
         return $this->em->createTableAlias($this->getTableName());
      }




      /**
       * @param string|null $alias
       * @return EntityManager
      */
      private function em(string $alias = null): EntityManager
      {
          $this->em->registerClass($this->entityClass);
          $this->em->alias($alias);

          return $this->em;
      }
      
      
      
      private function registerClass($entityClass)
      {
          
      }
}