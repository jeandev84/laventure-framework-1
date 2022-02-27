<?php
namespace Laventure\Component\Database\Builder\SQL;


use Laventure\Component\Database\Builder\Common\SqlBuilder;


/**
 * @DeleteBuilder
*/
class DeleteBuilder extends SqlBuilder
{


    /**
     * @param string $table
    */
    public function __construct(string $table)
    {
         $this->table = $table;
    }



    /**
     * @inheritDoc
    */
    protected function buildPrependSQL(): string
    {
         return sprintf("DELETE FROM %s", $this->table);
    }
}