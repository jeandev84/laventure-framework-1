<?php
namespace Laventure\Component\Database\ORM\Repository\Persistence;



use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Repository\Persistence\Common\AbstractPersistence;


/**
 * @PdoPersistence
*/
class PdoPersistence extends AbstractPersistence
{

    /**
     * @inheritDoc
    */
    public function retrieve(int $id)
    {
        $qb = $this->selectQuery()
                   ->where('id = :id')
                   ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }



    /**
     * @inheritDoc
    */
    public function delete(int $id)
    {
         $this->deleteQuery()
              ->where('id = :id')
              ->setParameter('id', $id)
              ->execute();
    }



    /**
     * @inheritDoc
    */
    public function update(array $attributes, $id)
    {
         $this->updateQuery($attributes)
              ->where('id = :id')
              ->setParameter('id', $id)
              ->execute();
    }



    /**
     * @inheritDoc
    */
    public function findQuery(array $criteria): Select
    {
         $qb = $this->selectQuery();

         foreach (array_keys($criteria) as $column) {
            $qb->where(sprintf('%s = :%s', $column, $column));
         }

         $qb->setParameters($criteria);

         return $qb;
    }
}