<?php
namespace Laventure\Component\Form;


use Laventure\Component\Form\Contract\FormChildrenTypeInterface;
use Laventure\Component\Form\Contract\FormInterface;
use Laventure\Component\Form\Exception\BadFormTypeException;
use Laventure\Component\Form\Types\TextType;


/**
 * @Form
*/
class Form //implements FormInterface
{


    /**
     * @var array
    */
    protected $config = [];




    /**
     * @var array
    */
    protected $children = [];



    /**
     * @var FormWrapper
    */
    protected $wrapper;




    /**
     * @var array
    */
    protected $templates = [];




    /**
     * @param array $config
    */
    public function __construct(array $config)
    {
         $this->config = $config;
         $this->wrapper = new FormWrapper();
    }




    /**
     * @inheritDoc
    */
    public function open(array $attributes)
    {
         $this->templates[] = $this->wrapper->openTag($attributes);
    }


    /**
     * @inheritDoc
     * @throws \ReflectionException
     * @throws BadFormTypeException
     */
    public function add(string $name, string $type = null, array $options = []): Form
    {
         if (! $type) {
             $type = TextType::class;
         }

         $child = $this->makeChildren($name, $type, $options);

         if (! $child instanceof FormChildrenTypeInterface) {
              throw new BadFormTypeException("Invalid type {$type}");
         }

         return $this->addChildren($child);
    }



    /**
     * @param FormChildrenTypeInterface $child
     * @return Form
    */
    public function addChildren(FormChildrenTypeInterface $child): Form
    {
         $child->setParent($this);
         $this->children[$child->getName()] = $child;
         $this->templates[$child->getName()] = $child->renderView();

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function close()
    {
        $this->templates[] = $this->wrapper->closeTag();
    }



    /**
     * @return string
    */
    public function renderView(): string
    {
        return join("\n", $this->templates);
    }




    /**
     * @param $childrenType
     * @param $childrenName
     * @param $options
     * @return object
     * @throws \ReflectionException
    */
    protected function makeChildren($childrenType, $childrenName, $options): object
    {
        $children = (new \ReflectionClass($childrenType))->newInstanceArgs($childrenName);
        $children->setOptions($options);

        return $children;
    }
}