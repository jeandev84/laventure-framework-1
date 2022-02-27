<?php
namespace Laventure\Component\Database\Connection;


use Laventure\Component\Database\Configuration\ConfigurationBag;
use Laventure\Component\Database\Connection\Exception\ConnectionLogicException;

/**
 * @ConnectionTrait
*/
trait ConnectionTrait
{


    /**
     * @var mixed
    */
    protected $connection;




    /**
     * @var ConfigurationBag
    */
    protected $config;





    /**
     * @param array $config
     * @return ConfigurationBag
    */
    public function parseConfigs(array $config): ConfigurationBag
    {
         return $this->config = new ConfigurationBag($config);
    }




    /**
     * @param $key
     * @param $default
     * @return mixed
    */
    public function config($key = null, $default = null)
    {
         return $this->config->get($key, $default);
    }




    /**
     * get all params connection configuration
     *
     * @return array
    */
    public function configs(): array
    {
        return $this->config->all();
    }




    /**
     * @return mixed
    */
    public function getConnection()
    {
        return $this->connection;
    }




    /**
     * @param $connection
     * @return void
    */
    public function setConnection($connection)
    {
         $this->connection = $connection;
    }


    /**
     * @return void
    */
    public function getName()
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @return void
    */
    public function createDatabase()
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @return void
    */
    public function dropDatabase()
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @param $table
     * @param string $columns
     * @param array $alterColumns
     * @return void
    */
    public function createTable($table, string $columns, array $alterColumns = [])
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @param $table
     * @return void
    */
    public function dropTable($table)
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @param $table
     * @return void
    */
    public function dropIfExistsTable($table)
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @param $table
     * @return void
    */
    public function truncateTable($table)
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @return void
    */
    public function showTables()
    {
        $this->abortIfLogicException(__METHOD__);
    }


    /**
     * @param $table
     * @return void
    */
    public function describeTable($table)
    {
        $this->abortIfLogicException(__METHOD__);
    }



    /**
     * @throws ConnectionLogicException
    */
    protected function abortIfLogicException($method)
    {
        throw new ConnectionLogicException("Method $method is not implement inside connection ". get_called_class());
    }
}