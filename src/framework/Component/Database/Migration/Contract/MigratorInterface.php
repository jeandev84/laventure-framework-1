<?php
namespace Laventure\Component\Database\Migration\Contract;


/**
 * @MigratorInterface
*/
interface MigratorInterface
{


      /**
       * Returns table name version migrations
       *
       * @return mixed
      */
      public function getMigratorTable();




      /**
       * Get migrations
       *
       * @return mixed
      */
      public function getMigrations();




      /**
       * Create a migration table
       *
       * @return mixed
      */
      public function createMigrationTable();





      /**
       * Create all schema database
       *
       * @return mixed
      */
      public function migrate();




      /**
       * Truncate all schema tables
       *
       * @return mixed
      */
      public function rollback();






      /**
       * Clear all schema table
       *
       * @return mixed
      */
      public function reset();
}