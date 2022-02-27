<?php
namespace Laventure\Component\Database\ORM\Builder;


use Laventure\Component\Database\Builder\SQL\DeleteBuilder;
use Laventure\Component\Database\ORM\Builder\Common\BuilderTrait;


/**
 * @Delete
*/
class Delete extends DeleteBuilder
{

    use BuilderTrait;


    /**
     * @param string $table
    */
    public function __construct(string $table)
    {
        parent::__construct($table);
    }
}