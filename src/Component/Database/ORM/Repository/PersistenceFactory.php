<?php
namespace Laventure\Component\Database\ORM\Repository;


use Exception;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\Mysqli\Contract\MysqliConnectionInterface;
use Laventure\Component\Database\Connection\Drivers\PDO\Contract\PdoConnectionInterface;
use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\Database\ORM\Repository\Common\Persistence;
use Laventure\Component\Database\ORM\Repository\Persistence\MysqliPersistence;
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
       * @return Persistence
       * @throws Exception
      */
      public function make(ConnectionInterface $connection): Persistence
      {
          if ($connection instanceof PdoConnectionInterface) {
              return new PdoPersistence($this->em);
          }

          if ($connection instanceof MysqliConnectionInterface) {
              return new MysqliPersistence($this->em);
          }

          throw new Exception("Cannot make persistence object for connection {$connection->getName()}");
      }
}