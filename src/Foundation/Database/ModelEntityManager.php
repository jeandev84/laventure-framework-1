<?php
namespace Laventure\Foundation\Database;



use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\Database\ORM\Exception\EntityManagerException;
use Laventure\Component\Database\ORM\Repository\EntityRepository;


/**
 * @ModelEntityManager
*/
class ModelEntityManager extends EntityManager
{

    /**
     * @inheritDoc
     * @throws EntityManagerException
    */
    public function getRepository($name): EntityRepository
    {
        throw new EntityManagerException(sprintf('unable method %s for class %s', __METHOD__, get_class()));
    }
}