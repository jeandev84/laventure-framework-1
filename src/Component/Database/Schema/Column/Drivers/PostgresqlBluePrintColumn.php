<?php
namespace Laventure\Component\Database\Schema\Column\Drivers;


use Laventure\Component\Database\Schema\Column\BluePrintColumn;
use Laventure\Component\Database\Schema\Column\Column;

/**
 * @PostgresqlBluePrintColumn
*/
class PostgresqlBluePrintColumn extends BluePrintColumn
{

    /**
     * @inheritDoc
    */
    public function increments($name): Column
    {
       return $this->add($name, 'SERIAL', null, '', true);
    }



    /**
     * @param $name
     * @param int $length
     * @return Column
    */
    public function integer($name, int $length = 11): Column
    {
        return $this->add($name, 'INTEGER', null);
    }




    /**
     * @param $name
     * @return Column
     */
    public function datetime($name): Column
    {
        return $this->add($name, 'TIMESTAMP', null);
    }



    /**
     * @param $name
     * @return Column
    */
    public function boolean($name): Column
    {
        return $this->add($name, 'BOOLEAN', null);
    }

}