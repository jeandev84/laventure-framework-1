<?php
namespace Laventure\Component\Database\ORM\Builder;



use Laventure\Component\Database\Builder\SQL\InsertBuilder;
use Laventure\Component\Database\ORM\Builder\Common\BuilderTrait;



/**
 * @Insert
*/
class Insert extends InsertBuilder
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