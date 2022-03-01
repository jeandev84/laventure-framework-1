<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Config\Config;
use Laventure\Component\Container\ServiceProvider\Contract\BootableServiceProvider;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Database\Connection\Exception\ConnectionException;
use Laventure\Component\Database\Manager;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Component\Database\ORM\Contract\EntityManagerInterface;
use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Database\ORM\Factory\EntityRepositoryFactory;


/**
 * @DatabaseServiceProvider
*/
class DatabaseServiceProvider extends ServiceProvider implements BootableServiceProvider
{

    /**
     * @var array
    */
    protected $aliases = [];




    public function boot()
    {
        // Registration database manager for working with repositories
        $this->app->singleton('db.laventure', function (Config $config) {
            return $this->makeConnection($config, new EntityRepositoryFactory($this->app));
        });


        // boot database Manager for working with Model
        $this->app->singleton(Manager::class, function (Config $config) {
            return $this->makeConnection($config);
        });

        // boot manager for working with models
        $this->app[Manager::class]->bootAsGlobal();
    }




    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->registrationEntityManager();
        $this->registrationMigrationServices();
    }



    /**
     * @return void
    */
    protected function registrationEntityManager()
    {
        $this->app->singleton(EntityManager::class, function () {
            return $this->app['db.laventure']->getEntityManager();
        });

        $this->app->singleton(EntityManagerInterface::class, EntityManager::class);
    }





    /**
     * @return void
    */
    protected function registrationMigrationServices()
    {
        $this->app->singleton(Migrator::class, function (EntityManager $em, FileSystem $fs) {
            $migrator = new Migrator($em);
            $migrator->addMigrations($this->loadMigrations($fs));
            return $migrator;
        });


        $this->app->singleton('db.schema', function (Manager $manager) {
            return $manager->schema();
        });
    }




    /**
     * @param FileSystem $fs
     * @return array
     * @throws \Exception
    */
    protected function loadMigrations(FileSystem $fs): array
    {
         $migrationFiles = $fs->loadResourceFileNames('app/Migration/*.php');

         $migrations = [];

         foreach ($migrationFiles as $migrationClass) {
             $migrationClass = sprintf('App\\Migration\\%s', $migrationClass);
             $migration = $this->app->get($migrationClass);
             $migrations[] = $migration;
         }

         return $migrations;
    }


    /**
     * @param Config $config
     * @param EntityRepositoryFactory|null $resolver
     * @return Manager
     * @throws ConnectionException
    */
    protected function makeConnection(Config $config, EntityRepositoryFactory $resolver = null): Manager
    {
        $manager = new Manager();
        $manager->addConnection($config['database'], $config['database']['connection']);

        $em = new EntityManager($manager->connection());

        if ($resolver) {
            $em->withRepositoryFactory($resolver);
        }

        $manager->withEntityManager($em);

        return $manager;
    }

}