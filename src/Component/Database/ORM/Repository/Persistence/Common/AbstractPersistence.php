<?php
namespace Laventure\Component\Database\ORM\Repository\Persistence\Common;


use Laventure\Component\Database\ORM\Builder\Delete;
use Laventure\Component\Database\ORM\Builder\Select;
use Laventure\Component\Database\ORM\Builder\Update;
use Laventure\Component\Database\ORM\Common\EntityManager;
use Laventure\Component\Database\ORM\Contract\PersistenceInterface;




/**
 * @AbstractPersistence
*/
abstract class AbstractPersistence implements PersistenceInterface
{


    /**
     * @var EntityManager
    */
    protected $em;



    /**
     * @var int
    */
    protected $id = 0;




    /**
     * @param EntityManager $em
    */
    public function __construct(EntityManager $em)
    {
         $this->em = $em;
    }


    /**
     * @return int
    */
    public function generateId(): int
    {
        return $this->id++;
    }



    /**
     * @param array $data
     * @return void
    */
    public function persist(array $data)
    {
        $attributes = $this->filteredAttributes($data);

        if (! empty($data['id'])) {
           $this->update($attributes, $data['id']);
        }else{
            $this->insert($attributes);
        }
    }
    
    


    /**
     * @param array $data
     * @return array
    */
    protected function filteredAttributes(array $data): array
    {
        unset($data['id']);

        return $data;
    }




    /**
     * @return Select
    */
    public function selectQuery(): Select
    {
        return $this->em->createQueryBuilder()
                        ->select(["*"])
                        ->from($this->em->getTableName());
    }



    /**
     * @param array $attributes
     * @return Update
    */
    protected function updateQuery(array $attributes): Update
    {
        return $this->em->createQueryBuilder()
                        ->update($attributes);
    }




    /**
     * @return Delete
    */
    public function deleteQuery(): Delete
    {
        return $this->em->createQueryBuilder()->delete();
    }



    /**
     * @param array $attributes
     * @return mixed
    */
    public function insert(array $attributes)
    {
        return $this->em->createQueryBuilder()->insert($attributes);
    }



    /**
     * @param array $attributes
     * @param $id
     * @return mixed
    */
    abstract public function update(array $attributes, $id);




    /**
     * @param array $attributes
     * @param array $wheres
     * @return mixed
    */
    abstract public function updateWheres(array $attributes, array $wheres);




    /**
     * @param array $wheres
     * @return mixed
    */
    abstract public function deleteWheres(array $wheres);




    /**
     * @param array $criteria
     * @return mixed
    */
    abstract public function findQuery(array $criteria);

}