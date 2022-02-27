<?php
namespace Laventure\Foundation\Service\Generator;


use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Service\Generator\Exception\StubGeneratorException;

/**
 * @StubGenerator
*/
class StubGenerator
{

     /**
      * @var FileSystem
     */
     public $fileSystem;




     /**
      * @var FileSystem
     */
     protected $stubGenerator;




     /**
      * @var array
     */
     protected $messageLog = [];




     /**
      * @param FileSystem $fileSystem
     */
     public function __construct(FileSystem $fileSystem)
     {
          $this->fileSystem    = $fileSystem;
          $this->stubGenerator = new FileSystem($this->getStubPath());
     }



     /**
      * @return string
     */
     protected function getStubPath(): string
     {
         return __DIR__.'/stubs';
     }



     /**
      * @return string
     */
     protected function getStubExtension(): string
     {
         return 'stub';
     }




     /**
      * @param $filename
      * @param $replacements
      * @return string|string[]
     */
     public function generateStub($filename, $replacements)
     {
         return  $this->stubGenerator->replace(
             sprintf('%s.%s', $filename, $this->getStubExtension()),
             $replacements
         );
     }


    /**
     * @param $targetPath
     * @param $stub
     * @return string
     * @throws StubGeneratorException
     */
     public function dumpStub($targetPath, $stub): string
     {
         try {

             $this->fileSystem->make($targetPath);
             $this->fileSystem->write($targetPath, $stub);

             return $this->fileSystem->locate($targetPath);

         } catch (\Exception $e) {

            $this->addMessageLog($e->getMessage());

            throw new StubGeneratorException($e->getMessage(), $e->getCode());
         }
     }



     /**
      * @param $message
      * @return self
     */
     protected function addMessageLog($message): self
     {
         $this->messageLog[] =  $message;

         return $this;
     }




     /**
      * @return array
     */
     public function getMessageLog(): array
     {
         return $this->messageLog;
     }




     /**
      * @param string $path
      * @return string
     */
     public function resolvedPath(string $path): string
     {
          return $this->fileSystem->resolvePath($path);
     }

}