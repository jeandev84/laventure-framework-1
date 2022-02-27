<?php
namespace Laventure\Component\Form\Types;


use Laventure\Component\Form\Contract\FormChildrenTypeInterface;
use Laventure\Component\Form\FormChildrenType;



/**
 *
*/
class TextAreaType extends FormChildrenType implements FormChildrenTypeInterface
{

    /**
     * @return string
    */
    public function renderView(): string
    {
         return sprintf('<textarea name="%s" %s></textarea>', $this->name, '[attributes=values]');
    }
}