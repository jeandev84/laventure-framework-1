<?php
namespace Laventure\Component\Database\Connection\Drivers\Mysqli\Statement;


use Laventure\Component\Database\Connection\Contract\QueryInterface;


/**
 * @Query
*/
class Query implements QueryInterface
{

    public function __construct(\mysqli $connection)
    {


    }



    /**
     * @inheritDoc
     */
    public function prepare(string $sql)
    {
        // TODO: Implement prepare() method.
    }



    /**
     * @inheritDoc
     */
    public function execute(array $params = [])
    {
        // TODO: Implement execute() method.
    }

    /**
     * @inheritDoc
     */
    public function getResult()
    {
        // TODO: Implement getResult() method.
    }

    /**
     * @inheritDoc
     */
    public function getOneOrNullResult()
    {
        // TODO: Implement getOneOrNullResult() method.
    }

    /**
     * @inheritDoc
     */
    public function getArrayColumns()
    {
        // TODO: Implement getArrayColumns() method.
    }

    /**
     * @inheritDoc
    */
    public function getFirstResult()
    {
        // TODO: Implement getFirstResult() method.
    }
}