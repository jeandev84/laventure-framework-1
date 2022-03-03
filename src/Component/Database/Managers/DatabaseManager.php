<?php
namespace Laventure\Component\Database\Managers;


use Exception;
use Laventure\Component\Database\Connection\ConnectionFactory;
use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Connection\Exception\ConnectionException;
use Laventure\Component\Database\Managers\Contract\DatabaseManagerInterface;
use RuntimeException;


/**
 * @DatabaseManager
*/
class DatabaseManager implements DatabaseManagerInterface
{


    /**
     * Current connection
     *
     * @var string
    */
    protected $connection;




    /**
     * @var array
    */
    protected $status = [];



    /**
     * @var ConnectionFactory
    */
    public $factory;



    /**
     * DatabaseManager constructor
    */
    public function __construct(array $config = [])
    {
        $this->factory = new ConnectionFactory($config);
    }




    /**
     * @param $name
     * @param array $config
     * @return void
    */
    public function connect($name, array $config)
    {
         if (! $this->connection) {
             $this->setDefaultConnection($name);
             $this->setConfiguration($name, $config);
         }
    }



    /**
     * @param ConnectionInterface $connection
     * @return self
    */
    public function setConnection(ConnectionInterface $connection): self
    {
         $this->factory->connections->add($connection);

         return $this;
    }




    /**
     * @param array $connections
     * @return self
    */
    public function setConnections(array $connections): self
    {
          $this->factory->connections->addConnections($connections);

          return $this;
    }





    /**
     * @param $name
     * @param $config
     * @return $this
    */
    public function setConfiguration($name, $config): self
    {
        if (isset($config[$name])) {
            $config = $config[$name];
        }

        $this->factory->configs->add($name, $config);

        return $this;
    }




    /**
     * @param array $config
     * @return $this
    */
    public function setConfigurations(array $config): self
    {
         $this->factory->configs->merge($config);

         return $this;
    }




    /**
     * get connection configuration params
     *
     * @param string $name
     * @return array
     * @throws
    */
    public function configuration(string $name): array
    {
        if (! $this->factory->configs->has($name)) {
            throw new RuntimeException(sprintf('empty config params for connection to (%s)', $name));
        }

        return $this->factory->configs->get($name);
    }





    /**
     * @param null $name
     * @return ConnectionInterface
     * @throws ConnectionException
    */
    public function connection($name = null): ConnectionInterface
    {
        if (! $name) {
            $name = $this->getDefaultConnection();
        }

        $config = $this->configuration($name);

        if (! $this->factory->connections->has($name)) {
            throw new ConnectionException("Could not get connection for {$name}.");
        }

        $connection = $this->factory->makeConnection($name, $config);

        $this->setConnectionStatus($connection);
        $this->setDefaultConnection($name);

        return $connection;
    }




    /**
     * @param $connection
     * @return
     */
    public function setDefaultConnection($connection)
    {
        $this->connection = $connection;
    }



    /**
     * @return string
     */
    public function getDefaultConnection(): string
    {
        return $this->connection;
    }






    /**
     * @param ConnectionInterface $connection
     * @return void
    */
    public function setConnectionStatus(ConnectionInterface $connection)
    {
        $this->status[$connection->getName()] = $connection->connected();
    }



    /**
     * @throws Exception
    */
    public function getConnectionStatus(string $name): bool
    {
        return $this->status[$name];
    }




    /**
     * @return array
    */
    public function getConnections(): array
    {
        return $this->factory->connections->all();
    }



    /**
     * @return array
    */
    public function getConfigurations(): array
    {
        return $this->factory->configs->all();
    }




    /**
     * @param string $name
    */
    public function remove(string $name)
    {
         $this->factory->remove($name);
    }




    /**
     * @param string|null $name
     * @throws Exception
    */
    public function purge(string $name = null)
    {
        $this->disconnect($name);

        $this->factory->connections->remove($name);
    }





    /**
     * @param string|null $name
     * @return ConnectionInterface
     * @throws Exception
    */
    public function reconnect(string $name = null): ConnectionInterface
    {
        if ($this->factory->connections->has($name)) {
            return $this->connection($name);
        }

        return $this->connection();
    }





    /**
     * @param $name
     * @return void
     * @throws ConnectionException
    */
    public function disconnect($name = null)
    {
         if ($this->factory->connections->has($name)) {
              $connection = $this->factory->connections->get($name);
              return $connection->disconnect();
         }

         return $this->connection()->disconnect();
    }




    /**
     * Create database
     *
     * @return void
     * @throws Exception
    */
    public function create()
    {
        return $this->connection()->createDatabase();
    }





    /**
     * @throws Exception
    */
    public function drop()
    {
        return $this->connection()->dropDatabase();
    }



    /**
     * @throws ConnectionException
    */
    public function exec($sql)
    {
        return $this->connection()->exec($sql);
    }

}