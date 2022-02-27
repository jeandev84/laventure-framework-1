<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Contract;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;


/**
 * @PdoConnectionInterface
*/
interface PdoConnectionInterface extends ConnectionInterface
{
    /**
     * Get connection to PDO
     *
     * @return \PDO
    */
    public function getPdo(): \PDO;

}