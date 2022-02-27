<?php
namespace Laventure\Component\Form\Common;

use Laventure\Component\Form\Contract\FormChildrenTypeInterface;
use Laventure\Component\Form\FormChildrenType;


/**
 * @TextType
 */
class TextType extends FormChildrenType implements FormChildrenTypeInterface
{

    /**
     * @var string
    */
    protected $type = 'text';



    /**
     * @return string
    */
    public function renderView(): string
    {
        return sprintf('<input type="%s" name="%s" %s>', $this->type, $this->name, '[attributes=values]');
    }
}