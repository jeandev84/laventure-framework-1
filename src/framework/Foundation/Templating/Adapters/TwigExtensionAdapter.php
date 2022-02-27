<?php
namespace Laventure\Foundation\Templating\Adapters;


use Laventure\Foundation\Templating\Contract\ViewAdapterInterface;
use Twig\Environment;



/**
 * @TwigExtensionAdapter
*/
class TwigExtensionAdapter implements ViewAdapterInterface
{


    /**
     * @var Environment
    */
    protected $renderer;




    /**
     * @param Environment $renderer
    */
    public function __construct(Environment $renderer)
    {
        $this->renderer = $renderer;
    }



    /**
     * @inheritDoc
    */
    public function render(string $template, array $variables = [])
    {
         return (function () use ($template, $variables) {
             return $this->renderer->render($template, $variables);
         })();
    }



    /**
     * @inheritDoc
    */
    public function getExtension()
    {
         return 'twig';
    }
}