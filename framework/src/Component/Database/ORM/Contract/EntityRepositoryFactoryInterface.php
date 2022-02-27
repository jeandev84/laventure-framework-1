<?php
namespace Laventure\Component\Database\ORM\Contract;


use Laventure\Component\Database\ORM\Repository\EntityRepository;



/**
 * @EntityRepositoryFactoryInterface
*/
interface EntityRepositoryFactoryInterface
{

     /**
      * @param string $entityClass
      * @return EntityRepository
     */
     public function createRepository(string $entityClass): EntityRepository;

}