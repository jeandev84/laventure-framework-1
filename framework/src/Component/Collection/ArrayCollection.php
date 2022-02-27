<?php
namespace Laventure\Component\Collection;


/**
 * @ArrayCollection
*/
class ArrayCollection
{

      /**
       * @var array
      */
      protected $elements = [];




      /**
       * @param object $element
       * @return $this
      */
      public function add(object $element): ArrayCollection
      {
           $this->elements[$element->getId()] = $element;

           return $this;
      }



      /**
       * @param object $element
       * @return bool
      */
      public function contains(object $element): bool
      {
          return \in_array($element, $this->elements);
      }



      /**
       * @param object $element
       * @return void
      */
      public function remove(object $element)
      {
           unset($this->elements[$element->getId()]);
      }
}