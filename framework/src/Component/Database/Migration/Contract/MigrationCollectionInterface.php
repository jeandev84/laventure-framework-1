<?php
namespace Laventure\Component\Database\Migration\Contract;


/**
 * @MigrationCollectionInterface
*/
interface MigrationCollectionInterface
{
      public function getMigrations();
      public function getMigrationFiles();
}