<?php
namespace Laventure\Foundation\Console\Commands\Database\Migration\Service;


use Laventure\Foundation\Service\Generator\Exception\StubGeneratorException;
use Laventure\Foundation\Service\Generator\StubGenerator;


/**
 * @MigrationFileGenerator
*/
class MigrationFileGenerator extends StubGenerator
{

     /**
      * @return string
     */
     protected function getStubPath(): string
     {
         return __DIR__ . '/stubs';
     }


    /**
     * @return string
     * @throws StubGeneratorException
     */
     public function generateMigrationFile(): string
     {
         $migrationClass = 'Version'. date('YmdHis');

         $stub = $this->generateStub('migration', [
             'MigrationClass'      => $migrationClass,
             'MigrationNamespace'  => 'App\\Migration',
             'tableName'           => 'tableName' // TODO dynamic
         ]);

         $targetPath = sprintf('app/Migration/%s.php', $migrationClass);
         $targetPath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $targetPath);

         return $this->dumpStub($targetPath, $stub);
     }
}