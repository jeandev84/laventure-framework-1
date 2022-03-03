<?php
namespace Laventure\Component\Database\ORM\Contract;



use Closure;
use Laventure\Component\Database\Builder\Contract\SqlQueryBuilderInterface;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Contract\QueryInterface;



/**
 * @EntityManagerInterface
*/
interface EntityManagerInterface extends ObjectManager
{


    /**
     * @return mixed
    */
    public function beginTransaction();




    /**
     * @return mixed
    */
    public function commit();





    /**
     * @return mixed
    */
    public function rollback();




    /**
     * @param $sql
     * @return mixed
    */
    public function exec($sql);






    /**
     * @return mixed
    */
    public function getClassMap();





    /**
     * @param object
     *
     * @return mixed
    */
    public function getClassMetadata(object $concrete);





    /**
     * @return mixed
    */
    public function getConnection();




    /**
     * @return ConnectionInterface
    */
    public function getConnectionManager(): ConnectionInterface;





    /**
     * @param $name
     * @return mixed
    */
    public function getRepository($name);




    /**
     * @return SqlQueryBuilderInterface
    */
    public function createQueryBuilder();




    /**
     * @return QueryInterface
    */
    public function createNativeQuery($sql);
}