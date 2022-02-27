<?php
namespace Laventure\Component\Database\Builder\Contract;


/**
 * @SqlQueryBuilderInterface
*/
interface SqlQueryBuilderInterface
{

    /**
     * @param array $selects
     * @return mixed
    */
    public function select(array $selects);




    /**
     * @param array $attributes
     * @param string $table
     * @return mixed
    */
    public function insert(array $attributes, string $table);




    /**
     * @param array $attributes
     * @param string $table
     * @return mixed
    */
    public function update(array $attributes, string $table);



    /**
     * @param $table
     * @return mixed
    */
    public function delete($table);
}