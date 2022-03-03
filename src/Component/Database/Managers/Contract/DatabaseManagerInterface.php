<?php
namespace Laventure\Component\Database\Managers\Contract;


/**
 * @DatabaseManagerInterface
*/
interface DatabaseManagerInterface
{


    /**
     * @param $name
     * @param array $config
     * @return mixed
    */
    public function connect($name, array $config);




    /**
     * @param $name
     * @return void
    */
    public function connection($name = null);





    /**
     * @param $name
     * @return mixed
    */
    public function disconnect($name = null);
}