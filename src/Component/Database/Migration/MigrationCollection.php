<?php
namespace Laventure\Component\Database\Migration;



use Laventure\Component\Database\Migration\Contract\MigrationCollectionInterface;
use Laventure\Component\Database\Migration\Contract\MigrationInterface;
use Laventure\Component\Database\Migration\Exception\MigrationException;



/**
 * @MigrationCollection
*/
class MigrationCollection implements MigrationCollectionInterface
{


      /**
       * @var MigrationInterface[]
      */
      protected $migrations = [];




      /**
       * @var array
      */
      protected $migrationFiles = [];




      /**
       * @param MigrationInterface $migration
       * @return MigrationInterface
      */
      public function addMigration(MigrationInterface $migration): MigrationInterface
      {
           $name = $migration->getName();

           $this->migrations[$name] = $migration;
           $this->migrationFiles[$name] = $migration->getPath();

           return $migration;
      }




      /**
       * @param array $migrations
       * @return void
      */
      public function addMigrations(array $migrations)
      {
           foreach ($migrations as $migration) {
                $this->addMigration($migration);
           }
      }





    /**
     * @param array $oldMigrations
     * @return array
    */
    public function getNewMigrations(array $oldMigrations): array
    {
        $migrations = [];

        foreach ($this->getMigrations() as $migration) {
            if (! \in_array($migration->getName(), $oldMigrations)) {
                $migrations[] = $migration;
            }
        }

        return $migrations;
    }




     /**
      * @param $name
      * @return bool
     */
     public function has($name): bool
     {
          return \array_key_exists($name, $this->migrations);
     }


    /**
     * @param MigrationInterface $migration
     * @return void
    */
    public function removeMigration(MigrationInterface $migration)
    {
        $name = $migration->getName();

        // check if migration exists
        if (! $this->has($name)) {
            throw new MigrationException("Migration '{$name}' is not available for removing.");
        }

        // remove migration from the list
        unset($this->migrations[$name]);


        // remove migration file
        $this->removeMigrationFile($migration);
    }




    /**
     * Remove migration files
     */
    public function removeMigrationFiles()
    {
        /* array_map('unlink', $this->getMigrationFilesToRemove()); */

        foreach ($this->getMigrations() as $migration) {
               $this->removeMigrationFile($migration);
        }
    }





    /**
     * @param MigrationInterface $migration
    */
    public function removeMigrationFile(MigrationInterface $migration)
    {
        @unlink($migration->getPath());

        unset($this->migrationFiles[$migration->getName()]);
    }




    /**
     * @return mixed
    */
    public function removeMigrations()
    {
        foreach ($this->migrations as $migration) {
            $this->removeMigration($migration);
        }
    }





    /**
     * @return MigrationInterface[]
     */
    public function getMigrations(): array
    {
        return $this->migrations;
    }




    /**
     * @return array
    */
    public function getMigrationFiles(): array
    {
        return $this->migrationFiles;
    }




    /**
     * @return array
    */
    public function getMigrationFilesToRemove(): array
    {
        return array_values($this->migrationFiles);
    }

}