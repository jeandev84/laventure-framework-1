<?php
namespace Laventure\Component\Database\ORM\Contract;


/**
 * @EntityRepositoryInterface
*/
interface EntityRepositoryInterface
{
    public function findOneBy(array $criteria);
    public function findAll();
}