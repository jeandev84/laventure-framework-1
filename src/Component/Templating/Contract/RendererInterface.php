<?php
namespace Laventure\Component\Templating\Contract;


/**
 * @RendererInterface
*/
interface RendererInterface
{


    /**
     * @return string
    */
    public function getResource(): string;



    /**
     * @param string $template
     * @return mixed
    */
    public function withTemplate(string $template);



    /**
     * @param array $variables
     * @return mixed
    */
    public function withVariables(array $variables);




    /**
     * @return mixed
    */
    public function renderTemplate();
}