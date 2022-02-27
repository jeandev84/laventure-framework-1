<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;
use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\Database\ORM\Contract\PersistenceInterface;
use Laventure\Component\Database\ORM\Repository\Persistence\Common\AbstractPersistence;
use Laventure\Component\Database\ORM\Repository\Persistence\PdoPersistence;

/**
 * @PersistenceFactory
*/
class PersistenceFactory
{


      /**
       * @var EntityManager
      */
      protected $em;




      /**
       * @param EntityManager $em
      */
      public function __construct(EntityManager $em)
      {
             $this->em = $em;
      }


      /**
       * @param ConnectionInterface $connection
       * @return AbstractPersistence
       * @throws \Exception
      */
      public function make(ConnectionInterface $connection): AbstractPersistence
      {
          if ($connection instanceof PdoConnection) {
              return new PdoPersistence($this->em);
          }

          throw new \Exception("Cannot make persistence object for connection {$connection->getName()}");
      }
}