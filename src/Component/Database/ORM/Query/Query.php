<?php
namespace Laventure\Component\Database\ORM\Query;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryClassMapInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;
use Laventure\Component\Database\Connection\Exception\StatementException;
use Laventure\Component\Database\Connection\Drivers\PDO\Statement\Query as PDOQuery;
use Laventure\Component\Database\ORM\EntityManager;


/**
 * @Query
*/
class Query
{

        /**
         * @var EntityManager
        */
        protected $em;



        /**
         * @var ConnectionInterface
        */
        protected $connection;




        /**
         * @var QueryInterface
        */
        protected $query;




        /**
         * @param EntityManager $em
        */
        public function __construct(EntityManager $em)
        {
              $this->em = $em;
              $this->connection = $em->getConnectionManager();
        }



        /**
         * @param string $sql
         * @param array $params
         * @return $this
        */
        public function query(string $sql, array $params = []): self
        {
              $query = $this->connection->query($sql, $params);
              $entityClass = $this->em->getClassMap();

              if ($entityClass && $query instanceof QueryClassMapInterface) {
                  $query->withEntity($entityClass);
              }

              $this->query = $query;

              return $this;
        }



        /**
         * @return array
        */
        public function getResult(): array
        {
            $results = $this->query->getResult();

            return $this->collects($results);
        }



        /**
         * @return void
        */
        public function execute()
        {
            $this->query->execute();
        }



        /**
         * @return mixed|void|null
        */
        public function getFirstResult()
        {
            $result = $this->query->getFirstResult();

            return $this->collect($result);
        }



        /**
         * @return false|mixed|object|void
        */
        public function getOneOrNullResult()
        {
            $result = $this->query->getOneOrNullResult();

            return $this->collect($result);
        }



        /**
         * @param $object
         * @return mixed
        */
        private function collect($object) {

             if (is_object($object)) {
                  $this->em->updates($object);
             }

             return $object;
        }



        /**
         * @param array $objects
         * @return array
        */
        private function collects(array $objects): array
        {
             foreach ($objects as $object) {
                  $this->collect($object);
             }

             return $objects;
        }
}