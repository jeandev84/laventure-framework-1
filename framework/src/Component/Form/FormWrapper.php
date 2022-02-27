<?php
namespace Laventure\Component\Form;


/**
 *
*/
class FormWrapper
{
     /**
       * @param array $attributes
       * @return string
      */
      public function openTag(array $attributes): string
      {
           return "<form>";
      }


      /**
       * @return string
      */
      public function closeTag(): string
      {
          return "</form>";
      }
}