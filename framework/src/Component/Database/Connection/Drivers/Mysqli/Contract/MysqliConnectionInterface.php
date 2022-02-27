<?php
namespace Laventure\Component\Database\Connection\Drivers\Mysqli\Contract;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;


/**
 * @MysqliConnectionInterface
*/
interface MysqliConnectionInterface extends ConnectionInterface
{
      public function getMysqli(): \mysqli;
}