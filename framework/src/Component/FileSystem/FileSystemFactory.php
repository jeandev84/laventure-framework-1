<?php
namespace Laventure\Component\FileSystem;



/**
 * @FileSystemFactory
*/
class FileSystemFactory
{

      /**
       * @var FileLocator
      */
      public $locator;




      /**
       * @var FileLoader
      */
      public $loader;



      /**
        * @var FileWriter
      */
      public $writer;




      /**
       * @var FileReader
      */
      public $reader;





      /**
        * @var FileUploader
      */
      public $uploader;




      /**
       * @var FileConvertor
      */
      public $convertor;



      /**
       * @param string|null $path
      */
      public function __construct(string $path = null)
      {
          $this->locator   = new FileLocator($path);
          $this->loader    = new FileLoader($path);
          $this->writer    = new FileWriter($path);
          $this->reader    = new FileReader($path);
          $this->convertor = new FileConvertor($path);
          $this->uploader  = new FileUploader();
      }
}