<?php
namespace Laventure\Component\Database\ORM\Repository\Persistence;



use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Repository\Common\Persistence;


/**
 * @PdoPersistence
*/
class PdoPersistence extends Persistence
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
         return $this->deleteWheres(['id' => $id]);
    }



    /**
     * @inheritDoc
    */
    public function update(array $attributes, $id)
    {
         $this->updateWheres($attributes, ['id' => $id]);
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





    /**
     * @inheritDoc
    */
    public function updateWheres(array $attributes, array $wheres)
    {
         $qb = $this->updateQuery($attributes);

         foreach (array_keys($wheres) as $column) {
            $qb->where("$column = :{$column}");
         }

         $qb->setParameters($wheres);

         $qb->execute();
    }




    /**
     * @inheritDoc
    */
    public function deleteWheres(array $wheres)
    {
        $qb = $this->deleteQuery();

        foreach (array_keys($wheres) as $column) {
            $qb->where("$column = :{$column}");
        }

        $qb->execute();
    }
}