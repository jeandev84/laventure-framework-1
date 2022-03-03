<?php
namespace Laventure\Component\Database\ORM\Repository\Common;



use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Managers\Exception\DatabaseManagerException;
use Laventure\Component\Database\Managers\Manager;
use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\Database\ORM\Repository\EntityRepository;


/**
 * @ActiveRecordRepository
*/
trait ActiveRecordRepository
{

    /**
     * @var EntityManager
     */
    protected $entityManager;




    /**
     * @var ConnectionInterface
    */
    protected $connection;




    /**
     * @var Persistence
     */
    protected $persistence;




    /**
     * @var EntityRepository
    */
    protected $repository;




    /**
     * @var
    */
    protected $table = '';





    /**
     * @var string
    */
    protected $primaryKey = 'id';





    /**
     * @throws DatabaseManagerException
    */
    public function __construct()
    {
        $this->initialize(Manager::instance());
    }




    /**
     * @param $id
     * @return false|mixed|object|void
    */
    public function findOne($id)
    {
        return $this->repository->findOneBy([$this->primaryKey => $id]);
    }






    /**
     * @return array
    */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }




    /**
     * @param array $attributes
     * @return void
     */
    public function insert(array $attributes)
    {
        $this->persistence->insert($attributes);
    }




    /**
     * @param array $attributes
     * @param $id
     * @return void
    */
    public function update(array $attributes, $id)
    {
        $this->persistence->updateWheres($attributes, [$this->primaryKey => $id]);
    }




    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        $this->persistence->deleteWheres([$this->primaryKey => $id]);
    }



    /**
     * @return string
     */
    protected function getTable(): string
    {
        return $this->table;
    }





    /**
     * @param Manager $db
     * @return void
    */
    private function initialize(Manager $db)
    {
        $entityManager = $db->getEntityManager();
        $entityManager->registerClass(get_called_class(), $this->getTable());
        $this->entityManager = $entityManager;
        $this->connection    = $entityManager->getConnectionManager();
        $this->repository    = $entityManager->createRepository();
        $this->persistence   = $entityManager->getPersistence();
    }
}