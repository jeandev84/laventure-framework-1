<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration\Common;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Foundation\Console\Commands\Database\Migration\Service\MigrationFileGenerator;


/**
 * @MigrationCommand
*/
abstract class MigrationCommand extends Command
{

      /**
       * @var Migrator
      */
      protected $migrator;




      /**
        * @var MigrationFileGenerator
      */
      protected $migrationStub;





      /**
       * @param Migrator $migrator
       * @param MigrationFileGenerator $migrationStub
       * @param string|null $name
      */
      public function __construct(Migrator $migrator, MigrationFileGenerator $migrationStub, string $name = null)
      {
          parent::__construct($name);
          $this->migrator = $migrator;
          $this->migrationStub = $migrationStub;
      }
}