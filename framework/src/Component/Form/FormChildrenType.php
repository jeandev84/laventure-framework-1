<?php
namespace Laventure\Component\Form;


/**
 * @FormChildren
*/
class FormChildrenType
{

     /**
      * @var string
     */
     protected $name;



     /**
      * @var mixed
     */
     protected $parent;



     /**
      * @var array
     */
     protected $options = [];




     /**
      * @param string $name
     */
     public function __construct(string $name)
     {
         $this->name = $name;
     }



     /**
      * @return string
     */
     public function getName()
     {
         return $this->name;
     }




     /**
      * @return mixed
     */
     public function getParent()
     {
         return $this->parent;
     }




     /**
      * @param $parent
      * @return void
     */
     public function setParent($parent)
     {
         $this->parent = $parent;
     }



     /**
      * @return array
     */
     public function getOptions()
     {
         return $this->options;
     }


     /**
      * @param $options
      * @return void
     */
     public function setOptions($options)
     {
         $this->options = $options;
     }
}