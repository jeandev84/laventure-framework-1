<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\ORM\EntityManager;


/**
 * @ServiceRepository
*/
class ServiceRepository extends EntityRepository
{
     /**
      * @param EntityManager $em
      * @param string $entityClass
     */
     public function __construct(EntityManager $em, string $entityClass)
     {
         parent::__construct($em, $entityClass);
     }
}