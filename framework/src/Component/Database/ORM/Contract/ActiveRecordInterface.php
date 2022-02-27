<?php
namespace Laventure\Component\Database\ORM\Contract;



/**
 * @ActiveRecordInterface
*/
interface ActiveRecordInterface
{

        /**
          * @param $id
          * @return mixed
        */
        public function findOne($id);




        /**
         * @return mixed
        */
        public function findAll();




        /**
         * @param array $attributes
         * @return mixed
        */
        public function insert(array $attributes);




        /**
         * @param array $attributes
         * @param array $wheres
         * @return mixed
        */
        public function update(array $attributes, array $wheres);




        /**
         * @param $id
         * @param array $criteria
         * @return mixed
        */
        public function delete($id, array $criteria = []);

}