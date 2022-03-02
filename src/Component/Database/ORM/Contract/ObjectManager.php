<?php
namespace Laventure\Component\Database\ORM\Contract;


/**
 * @ObjectManager
*/
interface ObjectManager
{

    /**
     * @param $object
     * @return mixed
    */
    public function persist($object);




    /**
     * @param $object
     * @return mixed
    */
    public function remove($object);





    /**
     * @return mixed
    */
    public function flush();
}