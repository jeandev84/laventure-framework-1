<?php
namespace Laventure\Component\Database\ORM\Contract;


use Laventure\Component\Database\Managers\DatabaseManagerInterface;


/**
 * @ManagerRegistryInterface
*/
interface ManagerRegistryInterface
{
    /**
     * @return DatabaseManagerInterface
    */
    public function getDatabaseManager(): DatabaseManagerInterface;


    /**
     * @return EntityManagerInterface
    */
    public function getEntityManager(): EntityManagerInterface;
}