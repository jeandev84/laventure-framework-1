<?php
namespace Laventure\Foundation\Database\ORM\Factory;


use Laventure\Component\Database\ORM\Contract\EntityRepositoryFactoryInterface;
use Laventure\Component\Database\ORM\Repository\EntityRepository;
use Laventure\Foundation\Application\Application;
use Laventure\Foundation\Database\Exception\EntityManagerException;


/**
 * @EntityRepositoryFactory
*/
class EntityRepositoryFactory implements EntityRepositoryFactoryInterface
{

      /**
       * @var Application
      */
      protected $app;



      /**
       * @param Application $app
      */
      public function __construct(Application $app)
      {
           $this->app = $app;

           // take namespace repository
      }



      /**
       * @inheritDoc
       * @throws EntityManagerException
      */
      public function createRepository(string $entityClass): EntityRepository
      {
           return (function () use ($entityClass) {

               $repositoryClassName = $this->createRepositoryName($entityClass);

               $repository = $this->app->get($repositoryClassName);

               if (! $repository instanceof EntityRepository) {
                   throw new EntityManagerException("Repository class {$entityClass} does not exist.");
               }

               return $repository;

           })();
      }




     /**
      * @param string $entityClass
      * @return string
      * @throws \ReflectionException
     */
     private function createRepositoryName(string $entityClass): string
     {
         $entityNamespace = (new \ReflectionClass($entityClass))->getNamespaceName();

         $repositoryClassName = str_replace($entityNamespace, "App\\Repository", $entityClass);

         return sprintf('%sRepository', $repositoryClassName);
     }
}