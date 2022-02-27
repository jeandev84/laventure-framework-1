<?php
namespace Laventure\Component\Database\Builder\Common;


use Laventure\Component\Database\Builder\Contract\SqlQueryBuilderInterface;
use Laventure\Component\Database\Builder\SQL\DeleteBuilder;
use Laventure\Component\Database\Builder\SQL\InsertBuilder;
use Laventure\Component\Database\Builder\SQL\SelectBuilder;
use Laventure\Component\Database\Builder\SQL\UpdateBuilder;



/**
 * @SqlQueryBuilder
*/
class SqlQueryBuilder implements SqlQueryBuilderInterface
{

    /**
     * @param array $selects
     * @return SelectBuilder
    */
    public function select(array $selects): SelectBuilder
    {
        return new SelectBuilder($selects);
    }



    /**
     * @param array $attributes
     * @param string $table
     * @return InsertBuilder
    */
    public function insert(array $attributes, string $table): InsertBuilder
    {
        return new InsertBuilder($attributes, $table);
    }


    /**
     * @param array $attributes
     * @param string $table
     * @return UpdateBuilder
    */
    public function update(array $attributes, string $table): UpdateBuilder
    {
        return new UpdateBuilder($attributes, $table);
    }





    /**
     * @param $table
     * @return DeleteBuilder
    */
    public function delete($table): DeleteBuilder
    {
        return new DeleteBuilder($table);
    }
}