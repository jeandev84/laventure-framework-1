<?php
namespace Laventure\Component\Container;


/**
 * @ContainerInterface
*/
interface ContainerInterface
{

    /**
     * @param $id
     * @return mixed
    */
    public function get($id);



    /**
     * @param $id
     * @return bool
    */
    public function has($id): bool;
}