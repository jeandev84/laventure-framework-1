<?php
namespace Laventure\Component\Database\ORM\Repository\Persistence;



use Laventure\Component\Database\ORM\Repository\Persistence\Common\AbstractPersistence;


/**
 * @MysqliPersistence
*/
class MysqliPersistence extends AbstractPersistence
{

    /**
     * @inheritDoc
    */
    public function generateId(): int
    {
        // TODO: Implement generateId() method.
    }

    /**
     * @inheritDoc
    */
    public function persist(array $data)
    {
        // TODO: Implement persist() method.
    }



    /**
     * @inheritDoc
    */
    public function retrieve(int $id)
    {
        // TODO: Implement retrieve() method.
    }



    /**
     * @inheritDoc
    */
    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @inheritDoc
     */
    public function update(array $attributes, $id)
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritDoc
     */
    public function findQuery(array $criteria)
    {
        // TODO: Implement find() method.
    }

    /**
     * @inheritDoc
     */
    public function updateWheres(array $attributes, array $wheres)
    {
        // TODO: Implement updateWheres() method.
    }

    /**
     * @inheritDoc
     */
    public function deleteWheres(array $wheres)
    {
        // TODO: Implement deleteWheres() method.
    }
}