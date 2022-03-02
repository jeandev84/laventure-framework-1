<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Common\EntityManager;


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
      public function createQueryBuilder(string $alias): Select
      {
          $this->em->withClass($this->entityClass);
          $this->em->withTableAlias($alias);

           return $this->em->createQueryBuilder()
                           ->select(["*"])
                           ->from($this->getTableName(), $alias);

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
              return $this->createQueryBuilder($this->getTableAlias())
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
}