<?php
namespace Laventure\Component\Database\ORM\Common;


use ReflectionObject;


/**
 * @DataMapper
*/
trait DataMapper
{

     /**
      * @param object $object
      * @return array
     */
     public function map(object $object): array
     {
         $attributes = [];
         $reflection = new ReflectionObject($object);

         foreach($reflection->getProperties() as $property) {
             $property->setAccessible(true);
             $attributes[$property->getName()] = $property->getValue($object);
         }

         return $attributes;
     }
}