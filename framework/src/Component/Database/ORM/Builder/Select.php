<?php
namespace Laventure\Component\Database\ORM\Builder;


use Laventure\Component\Database\Builder\SQL\SelectBuilder;
use Laventure\Component\Database\ORM\Builder\Common\BuilderTrait;


/**
 * @Select
*/
class Select extends SelectBuilder
{

     use BuilderTrait;


     /**
      * @param array $selects
     */
     public function __construct(array $selects = ["*"])
     {
         parent::__construct($selects);
     }
}