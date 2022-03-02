<?php
namespace Laventure\Foundation\Database;


use Exception;
use Laventure\Component\Container\Container;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\ORM\Common\EntityManager as AbstractEntityManager;
use Laventure\Component\Database\ORM\Exception\EntityManagerException;
use Laventure\Component\Database\ORM\Repository\EntityRepository;
use ReflectionClass;
use ReflectionException;


/**
 * @EntityManager
*/
class EntityManager extends AbstractEntityManager
{

      /**
       * @var Container
      */
      protected $app;



      /**
       * @param ConnectionInterface $connection
       * @param Container $app
       * @throws Exception
      */
      public function __construct(ConnectionInterface $connection, Container $app)
      {
          parent::__construct($connection);
          $this->app = $app;
      }




      /**
       * @param $name
       * @return EntityRepository
      */
      public function getRepository($name): EntityRepository
      {
          return (function () use ($name) {

              $repositoryClass = $this->createRepositoryName($name);

              $repository = $this->app->get($repositoryClass);

              if (! $repository instanceof EntityRepository) {
                  throw new EntityManagerException("Repository class $name does not exist.");
              }

              return $repository;

          })();
      }



     /**
      * @param string $entityClass
      * @return string
      * @throws ReflectionException
     */
    private function createRepositoryName(string $entityClass): string
    {
        $entityNamespace = (new ReflectionClass($entityClass))->getNamespaceName();

        $repositoryClassName = str_replace($entityNamespace, "App\\Repository", $entityClass);

        return sprintf('%sRepository', $repositoryClassName);
    }
}