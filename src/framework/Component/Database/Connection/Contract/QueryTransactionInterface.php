<?php
namespace Laventure\Component\Database\Connection\Contract;


/**
 * @QueryTransactionInterface
*/
interface QueryTransactionInterface
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

}