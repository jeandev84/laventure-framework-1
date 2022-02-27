<?php
namespace Laventure\Component\Console\Input;


use Laventure\Component\Console\Input\Common\InputParameter;



/**
 * @InputOption
*/
class InputOption extends InputParameter
{

     const NONE = 4;


     /**
      * @return bool
     */
     public function isNone(): bool
     {
         return $this->mode === self::NONE;
     }
}