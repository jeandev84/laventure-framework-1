<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Repository\Persistence\Common\AbstractPersistence;


/**
 * Persistence manager
 *
 * @Persistence
*/
class Persistence
{

    /**
     * @var AbstractPersistence
    */
    protected $persistence;



    /**
     * @var array
    */
    protected $deletes  = [];




    /**
     * @var array
    */
    protected $persists = [];




    /**
     * @param AbstractPersistence $persistence
    */
    public function __construct(AbstractPersistence $persistence)
    {
          $this->persistence = $persistence;
    }

    
    
    
    /**
     * @param array $criteria
     * @return Select
    */
    public function findQuery(array $criteria): Select
    {
         return $this->persistence->findQuery($criteria);
    }
    
    


    /**
     * @return int
    */
    public function generateId(): int
    {
        return $this->persistence->generateId();
    }


    /**
     * @param $id
     * @return mixed
    */
    public function retrieve($id)
    {
        return $this->persistence->retrieve($id);
    }




    /**
     * @param array $data
     * @return $this
    */
    public function persist(array $data): self
    {
        $id = $data['id'] ?? $this->generateId();

        $this->persists[$id] = $data;

        return $this;
    }




    /**
     * @param int $id
     * @return $this
    */
    public function remove(int $id): self
    {
        $this->deletes[] = $id;

        return $this;
    }



    /**
     * Flush method is a transaction queries
     *
     * @return void
    */
    public function flush()
    {
        $this->saveStack();
        $this->deleteStack();
    }




    /**
     * @param array $attributes
     * @param $id
     * @return mixed
    */
    public function update(array $attributes, $id)
    {
         return $this->persistence->update($attributes, $id);
    }





    /**
     * @param array $attributes
     * @return void
    */
    public function insert(array $attributes)
    {
        return $this->persistence->insert($attributes);
    }




    /**
     * @param int $id
     * @return mixed
    */
    public function delete(int $id)
    {
        return $this->persistence->delete($id);
    }




    /**
     * @param array $attributes
     * @param array $wheres
     * @return mixed
    */
    public function updateWheres(array $attributes, array $wheres)
    {
        return $this->persistence->updateWheres($attributes, $wheres);
    }




    /**
     * @param array $wheres
     * @return mixed
    */
    public function deleteWheres(array $wheres)
    {
         return $this->persistence->deleteWheres($wheres);
    }



    /**
     * @return array
    */
    public function getDeletes(): array
    {
        return $this->deletes;
    }




    /**
     * @return void
    */
    public function saveStack()
    {
        if ($this->persists) {
            foreach ($this->persists as $attributes) {
                $this->persistence->persist($attributes);
            }
        }
    }



    /**
     * @return void
    */
    public function deleteStack()
    {
        if ($this->deletes) {
            foreach ($this->deletes as $id) {
                $this->persistence->delete($id);
            }
        }
    }


}