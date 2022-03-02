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
         * @param $id
         * @return mixed
        */
        public function update(array $attributes, $id);





        /**
         * @param $id
         * @return mixed
        */
        public function delete($id);

}