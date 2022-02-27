<?php
namespace Laventure\Component\Database\ORM\Contract;



/**
 * @CrudInterface
*/
interface CrudInterface
{

      /**
       * @param array $attributes
       * @return mixed
      */
      public function create(array $attributes);




      /**
        * @param int $id
        * @return mixed
      */
      public function read(int $id);





      /**
        * @param array $attributes
        * @param int $id
        * @return mixed
      */
      public function update(array $attributes, int $id);





      /**
       * @param int $id
       * @return mixed
      */
      public function delete(int $id);
}