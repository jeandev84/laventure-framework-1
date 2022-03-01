<?php
namespace Laventure\Component\Database\Schema\Column;


/**
 * @ColumnCollection
*/
class ColumnCollection
{



    /**
     * @var Column[]
    */
    protected $columns = [];





    /**
     * @param $name
     * @param Column $column
     * @return Column
    */
    public function addColumn($name, Column $column): Column
    {
        $this->columns[$name] = $column;

        return $column;
    }




    /**
     * @param $name
     * @return bool
    */
    public function hasColumn($name): bool
    {
        return isset($this->columns[$name]);
    }



    /**
     * @param $name
     * @return Column|null
    */
    public function getColumn($name): ?Column
    {
        return $this->columns[$name] ?? null;
    }


    /**
     * @param $name
     * @return void
    */
    public function removeColumn($name)
    {
        unset($this->columns[$name]);
    }




    /**
     * @return bool
    */
    public function isEmpty(): bool
    {
        return empty($this->columns);
    }




    /**
     * @return int
    */
    public function count(): int
    {
        return count($this->columns);
    }





    /**
     * @return Column[]
    */
    public function getColumns(): array
    {
        return $this->columns;
    }
}