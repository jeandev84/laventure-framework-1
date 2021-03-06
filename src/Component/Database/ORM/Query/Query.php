<?php
namespace Laventure\Component\Database\ORM\Query;


use Laventure\Component\Database\Connection\Contract\QueryInterface;
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
         * @var QueryInterface
        */
        protected $query;


        /**
         * @param EntityManager $em
        */
        public function __construct(EntityManager $em)
        {
              $this->em    = $em;
              $this->query = $em->getQuery();
        }




        /**
         * @return void
        */
        public function execute()
        {
            $this->query->execute();
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
         * @return mixed
        */
        public function getArrayColumns()
        {
            return $this->query->getArrayColumns();
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