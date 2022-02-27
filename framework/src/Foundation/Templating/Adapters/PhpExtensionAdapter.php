<?php
namespace Laventure\Foundation\Templating\Adapters;



use Laventure\Component\Templating\Contract\ViewInterface;
use Laventure\Foundation\Templating\Contract\ViewAdapterInterface;


/**
 * @PhpExtensionAdapter
*/
class PhpExtensionAdapter implements ViewAdapterInterface
{


    /**
     * @var ViewInterface
    */
    protected $render;




    /**
     * @param ViewInterface $renderer
    */
    public function __construct(ViewInterface $renderer)
    {
         $this->render = $renderer;
    }





    /**
     * @inheritDoc
    */
    public function render(string $template, array $variables = [])
    {
          return $this->render->render($template, $variables);
    }



    /**
     * @inheritDoc
    */
    public function getExtension()
    {
          return 'php';
    }
}