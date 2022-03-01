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
    protected $insertions = [];



    /**
     * @var array
    */
    protected $updates = [];




    /**
     * @var array
    */
    protected $deletions  = [];




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
     * @return void
    */
    public function retrieve($id)
    {
        $this->persistence->retrieve($id);
    }




    /**
     * @param array $data
     * @return void
    */
    public function persist(array $data)
    {
        $id = $this->generateId();

        if (! empty($data['id'])) {
            $id = $data['id'];
            $this->updates[$id] = $data;
        }else{
            $this->insertions[$id] = $data;
        }

        $this->persists[$id] = $data;
    }




    /**
     * @param int $id
     * @return void
    */
    public function delete(int $id)
    {
        $this->deletions[$id] = $id;
    }



    /**
     * Flush method is a transaction queries
     *
     * @return void
    */
    public function flush()
    {
        $this->flushPersists();
        $this->flushDeletions();
    }



    protected function flushPersists()
    {
        foreach ($this->persists as $attributes) {
            $this->persistence->persist($attributes);
        }
    }



    protected function flushDeletions()
    {
        foreach ($this->deletions as $id) {
            $this->persistence->delete($id);
        }
    }




    /**
     * @return array
    */
    public function getInsertions(): array
    {
        return $this->insertions;
    }



    /**
     * @return array
    */
    public function getDeletions(): array
    {
        return $this->deletions;
    }

}