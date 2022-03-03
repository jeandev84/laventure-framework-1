<?php
namespace Laventure\Component\Database\ORM\Contract;


/**
 * @ModelRelationShipInterface
*/
interface EntityRelationShipInterface
{
      /**
       * @param string $class
       * @param string|null $column
       * @return mixed
      */
      public function belongsTo(string $class, string $column = null);




      /**
       * @param string $class
       * @param string|null $column
       * @return mixed
      */
      public function hasMany(string $class, string $column = null);
}