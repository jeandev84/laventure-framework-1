<?php
namespace Laventure\Component\Database\ORM\Builder;


use Laventure\Component\Database\Builder\SQL\UpdateBuilder;
use Laventure\Component\Database\ORM\Builder\Common\BuilderTrait;


/**
 * @Update
*/
class Update extends UpdateBuilder
{

      use BuilderTrait;


     /**
      * @param array $attributes
      * @param string $table
     */
     public function __construct(array $attributes, string $table)
     {
         parent::__construct($attributes, $table);
     }
}