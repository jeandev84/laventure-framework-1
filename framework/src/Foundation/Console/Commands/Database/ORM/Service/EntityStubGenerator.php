<?php
namespace Laventure\Foundation\Console\Commands\Database\ORM\Service;

use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Foundation\Service\Generator\StubGenerator;


/**
 * @EntityStubGenerator
*/
class EntityStubGenerator extends StubGenerator
{

    /**
     * @return string
    */
    protected function getStubPath(): string
    {
        return __DIR__ . '/stubs';
    }


    /**
     * @param $filename
     * @param $replacements
     * @return string|string[]
    */
    public function generateStubEntity($filename, $replacements)
    {
         return $this->generateStub("entity/$filename", $replacements);
    }



    /**
     * @param $filename
     * @param $replacements
     * @return string|string[]
     */
    public function generateStubModel($filename, $replacements)
    {
        return $this->generateStub("model/$filename", $replacements);
    }


    /**
     * @param string $concrete
     * @param array $properties
     * @return string
     */
    public function generateEntity(string $concrete, array $properties = []): string
    {
         $params = explode('/', trim($concrete, '\\/'));

         if (empty($params)) {
             return "no argument parsed for generate files.";
         }

         $entityClass = end($params);
         $module = str_replace(['/', $entityClass], ['\\', ''], $concrete);
         $module = trim($module, '\\');

         $namespaceEntity = 'App\\Entity'. ($module ? '\\'. $module : '');
         $namespaceRepository = 'App\\Repository'. ($module ? '\\'. $module : '');


         $stubEntity = $this->generateStubEntity('entity', [
             'EntityNamespace' => $namespaceEntity,
             'EntityClass'     => $entityClass
         ]);

         $stubRepository = $this->generateStubEntity('repository', [
            'RepositoryNamespace' => $namespaceRepository,
            'FullNameEntityClass' => sprintf('%s\\%s', $namespaceEntity, $entityClass),
            'RepositoryName'      => sprintf('%sRepository', $entityClass),
            'EntityClass'         => $entityClass
         ]);


         $entityPath     = $this->resolvedPath(sprintf('app/Entity/%s.php', $concrete));
         $repositoryPath = $this->resolvedPath(sprintf('app/Repository/%sRepository.php', $concrete));

         if ($this->fileSystem->exists($entityPath)) {
               return "Entity class '$entityClass' already generated.";
         }

         if (! $this->dumpStub($entityPath, $stubEntity)) {
              return "Something went wrong during generation entity file {$entityPath}";
         }

         if (! $this->dumpStub($repositoryPath, $stubRepository)) {
             return "Something went wrong during generation entity repository file {$repositoryPath}";
         }

         $msg[] =  "Entity class {$entityPath} successfully created.";
         $msg[] =  "Repository class class {$repositoryPath} successfully created.";

         return implode("\n", $msg);
    }



    /**
     * @param string $modelName
     * @return string
    */
    public function generateModel(string $modelName): string
    {
        $params = explode('/', trim($modelName, '\\/'));

        if (empty($params)) {
            return "no argument parsed for generate files.";
        }

        $modelClass = end($params);
        $module = str_replace(['/', $modelClass], ['\\', ''], $modelName);
        $module = trim($module, '\\');


        $namespaceModel = sprintf('App\\Model%s', $module ? '\\'. $module : '');

        //todo more dynamic model name .
        $stubModel = $this->generateStubModel('model', [
            'EntityNamespace' => $namespaceModel,
            'EntityClass'     => $modelClass,
            'TableName'       => strtolower(trim($modelClass, 's')) . 's'
        ]);


        $modelPath = $this->resolvedPath(sprintf('app/Model/%s.php', $modelName));

        if ($this->fileSystem->exists($modelPath)) {
            return "Model class '$modelClass' already generated.";
        }

        if (! $this->dumpStub($modelPath, $stubModel)) {
            return "Something went wrong during generation entity file {$modelPath}";
        }


        return "Model class {$modelClass} successfully created.";
    }




    /**
     * @param string $modelClass
     * @param array $properties
     * @return string
    */
    public function generateModelFile(string $modelClass, array $properties = []): string
    {
         return "";
    }
}