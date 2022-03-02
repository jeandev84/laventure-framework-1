<?php
namespace Laventure\Foundation\Database;



use Laventure\Component\Database\ORM\Contract\CrudInterface;
use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\Database\ORM\Repository\Common\Persistence;


/**
 * @Crud
*/
class Crud implements CrudInterface
{


    /**
     * @var EntityManager
    */
    protected $em;




    /**
     * @var Persistence
    */
    protected $persistence;




    /**
     * @param EntityManager $em
     *
     *  $crud = new Crud($em);
    */
    public function __construct(EntityManager $em, string $class, string $table = null)
    {
         $em->registerClass($class, $table);
         $this->persistence = $em->getPersistence();
         $this->em = $em;
    }




    /**
     * @param string $class
     * @param string|null $table
     * @return void
    */
    public function with(string $class, string $table = null)
    {
         $this->em->registerClass($class, $table);
    }



    /**
     * @inheritDoc
    */
    public function create(array $attributes)
    {
        $this->persistence->insert($attributes);
    }




    /**
     * @inheritDoc
    */
    public function read(int $id)
    {
        $this->persistence->retrieve($id);
    }





    /**
     * @inheritDoc
    */
    public function update(array $attributes, int $id)
    {
        $this->persistence->update($attributes, $id);
    }




    /**
     * @inheritDoc
    */
    public function delete(int $id)
    {
        $this->persistence->delete($id);
    }
}