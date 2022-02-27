<?php
namespace Laventure\Component\Database\ORM\Collection;


/**
 * @ObjectCollection
*/
class ObjectCollection
{

     /**
      * @var array
     */
     protected $objects = [];



     /**
      * @param array $objects
     */
     public function __construct(array $objects = [])
     {
          if ($objects) {
              $this->addToCollections($objects);
          }
     }



     /**
      * @param object $object
      * @return $this
     */
     public function add(object $object): ObjectCollection
     {
         $this->objects[] = $object;

         return $this;
     }




     /**
      * @param array $objects
      * @return void
     */
     public function addToCollections(array $objects)
     {
          foreach ($objects as $object) {
              $this->add($object);
          }
     }



     /**
      * @return array
     */
     public function getCollections(): array
     {
         return $this->objects;
     }
}