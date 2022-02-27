<?php
namespace Laventure\Component\Database\Schema\Column\Drivers;



use Laventure\Component\Database\Schema\Column\BluePrintColumn;
use Laventure\Component\Database\Schema\Column\Column;


/**
 * @MysqlBluePrintColumn
*/
class MysqlBluePrintColumn extends BluePrintColumn
{

    /**
     * @param $name
     * @return Column
    */
    public function increments($name): Column
    {
        return $this->add($name, 'int', 11, null, 'AUTO_INCREMENT');
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