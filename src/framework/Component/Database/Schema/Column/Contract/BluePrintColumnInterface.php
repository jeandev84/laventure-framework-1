<?php
namespace Laventure\Component\Database\Schema\Column\Contract;


/**
 * @BluePrintColumnInterface
*/
interface BluePrintColumnInterface
{

    /**
     * @param $name
     * @return mixed
    */
    public function increments($name);



    /**
     * @param $name
     * @param int $length
     * @return mixed
    */
    public function integer($name, int $length = 11);




    /**
     * @param string $name
     * @param int $length
     * @return mixed
    */
    public function string(string $name, int $length = 255);




    /**
     * @param $name
     * @return mixed
    */
    public function boolean($name);





    /**
     * @param $name
     * @return mixed
    */
    public function text($name);




    /**
     * @param $name
     * @return mixed
    */
    public function datetime($name);




    /**
     * @return void
    */
    public function timestamps();




    /**
     * @param bool $status
     * @return void
    */
    public function softDeletes(bool $status = false);





    /**
     * @return string
    */
    public function printColumns(): string;
}