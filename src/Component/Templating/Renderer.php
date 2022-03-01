<?php
namespace Laventure\Component\Templating;


use Laventure\Component\Templating\Contract\RendererInterface;
use Laventure\Component\Templating\Contract\ViewFactoryInterface;
use Laventure\Component\Templating\Exception\RendererException;




/**
 * @Renderer
*/
class Renderer implements RendererInterface, ViewFactoryInterface
{



    /**
     * view directory
     *
     * @var string
    */
    protected $resource;



    /**
     * file template
     *
     * @var string
    */
    protected $template;


    /**
     * layout of view
     *
     * @var string
    */
    protected $layout = '';




    /**
     * view data
     *
     * @var array
    */
    protected $variables = [];




    /**
     * Renderer constructor.
     *
     * @param string|null $resource
    */
    public function __construct(string $resource = null)
    {
        if($resource) {
            $this->withResource($resource);
        }
    }





    /**
     * @param string $resource
     * @return $this
    */
    public function withResource(string $resource): Renderer
    {
        $this->resource = rtrim($resource, '\\/');

        return $this;
    }



    /**
     * @return string
    */
    public function getResource(): string
    {
        return $this->resource;
    }




    /**
     * @param string $layout
     * @return $this
    */
    public function withLayout(string $layout): Renderer
    {
        $this->layout = $layout;

        return $this;
    }



    /**
     * @param array $variables
     * @return $this
    */
    public function withVariables(array $variables): Renderer
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }




    /**
     * @param string $template
     * @return $this
    */
    public function withTemplate(string $template): Renderer
    {
        $this->template = $template;

        return $this;
    }




    /**
     * Render view template and optional data
     *
     * @return false|string
     * @throws RendererException
    */
    public function renderTemplate(): string
    {
        extract($this->variables, EXTR_SKIP);

        ob_start();
        require_once($this->load($this->template));
        return ob_get_clean();
    }



    /**
     * Render html template with defined variables
     *
     * @param string $template
     * @param array $variables
     * @return false|string
     * @throws RendererException
     */
    public function render(string $template, array $variables = []): string
    {
       $content = $this->withTemplate($template)
                       ->withVariables($variables)
                       ->renderTemplate();

       return $this->renderContent($content);
    }



    /**
     * @throws RendererException
    */
    public function renderContent($content)
    {
        if ($layout = $this->renderLayout()) {
            return str_replace("{{ content }}", $content, $layout);
        }

        return $content;
    }


    /**
     * @return string
     * @throws RendererException
    */
    public function renderLayout()
    {
        if (! $this->exists($this->layout)) {
            return false;
        }

        ob_start();
        require_once ($this->load($this->layout));
        return ob_get_clean();
    }





    /**
     * @param string $template
     * @return string
     * @throws RendererException
    */
    public function load(string $template): string
    {
        $templatePath = $this->templatePath($template);

        if(! $this->exists($template)) {
            throw new RendererException(sprintf('view file %s does not exist!', $templatePath));
        }

        return realpath($templatePath);
    }



    /**
     * @param string|null $template
     * @return bool
    */
    public function exists(string $template): bool
    {
        $path = $this->templatePath($template);

        return \is_file($path); // or \is_readable($path);
    }



    /**
     * @param string $template
     * @return string
    */
    public function templatePath(string $template): string
    {
        return $this->resource . DIRECTORY_SEPARATOR . $this->resolvePath($template);
    }




    /**
     * @param $path
     * @return string|string[]
    */
    protected function resolvePath($path)
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, ltrim($path, '\\/'));
    }




    /**
     * @return string
    */
    public function getExtension(): string
    {
         return 'php';
    }


}