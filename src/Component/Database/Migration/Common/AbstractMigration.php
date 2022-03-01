<?php
namespace Laventure\Component\Database\Migration\Common;



use Laventure\Component\Database\Migration\Contract\MigrationInterface;



/**
 * @AbstractMigration
*/
abstract class AbstractMigration implements MigrationInterface
{


      /**
       * @var string
      */
      protected $name;




      /**
       * @var string
      */
      protected $path;




      /**
       * @return string
      */
      public function getName(): string
      {
           if ($this->name) {
              return $this->name;
           }

           return $this->name = (new \ReflectionClass(get_called_class()))->getShortName();
      }



     /**
      * @param string $name
      * @return $this
     */
     public function name(string $name): self
     {
         $this->name = $name;

         return $this;
     }



     /**
      * @return false|string
     */
     public function getPath()
     {
         if ($this->path) {
             return $this->path;
         }

         return $this->path = (new \ReflectionClass(get_called_class()))->getFileName();
     }




     /**
      * @param string $path
      * @return $this
     */
     public function path(string $path): self
     {
         $this->path = $path;

         return $this;
     }
}