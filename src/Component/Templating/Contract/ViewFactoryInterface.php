<?php
namespace Laventure\Component\Templating\Contract;



/**
 * @RenderFactoryInterface
*/
interface ViewFactoryInterface
{

    /**
     * @param string $template
     * @param array $variables
     * @return mixed
    */
    public function render(string $template, array $variables = []);

}