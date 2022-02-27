<?php
namespace Laventure\Component\Templating;


use Laventure\Component\Templating\Contract\AssetInterface;



/**
 * @Asset
*/
class Asset implements AssetInterface
{

    const STYLE_MASK   = '<link href="%s" rel="stylesheet">';
    const SCRIPT_MASK  = '<script src="%s" type="application/javascript"></script>';


    /**
     * @var array
    */
    private $styles = [];



    /**
     * @var array
    */
    private $scripts = [];



    /**
     * @var string
    */
    private $path;



    /**
     * Asset constructor.
     * @param string $path
    */
    public function __construct(string $path = '')
    {
        if($path) {
            $this->baseUrl($path);
        }
    }



    /**
     * @param $path
    */
    public function baseUrl($path)
    {
        $this->path = rtrim($path, '\\/');
    }




    /**
     * Add css link
     *
     * @param string $style
    */
    public function css(string $style)
    {
        $this->styles[] = $style;
    }




    /**
     * @param array $styles
    */
    public function addStyles(array $styles)
    {
        $this->styles = array_merge($this->styles, $styles);
    }




    /**
     * @param array $scripts
    */
    public function addScripts(array $scripts)
    {
        $this->scripts = array_merge($this->scripts, $scripts);
    }




    /**
     * Get css data
     *
     * @return array
    */
    public function getStyles(): array
    {
        return $this->styles;
    }


    /**
     * Add js link
     *
     * @param string $script
    */
    public function js(string $script)
    {
        $this->scripts[] = $script;
    }




    /**
     * Get css data
     *
     * @return array
    */
    public function getScripts(): array
    {
        return $this->scripts;
    }



    /**
     * @param string $link
     * @return string
    */
    public function renderCss(string $link = ''): string
    {
        if ($link) {
            return sprintf(self::STYLE_MASK."\n", $this->generatePath($link));
        }

        return $this->renderTemplate($this->styles, self::STYLE_MASK);
    }




    /**
     * @param string $script
     * @return string
    */
    public function renderJs(string $script = ''): string
    {
        if ($script) {
            return sprintf(self::SCRIPT_MASK."\n", $this->generatePath($script));
        }

        return $this->renderTemplate($this->scripts, self::SCRIPT_MASK);
    }




    /**
     * @param $filename
     * @return string
    */
    public function generatePath($filename): string
    {
        return $this->path . '/'. trim($filename, '\\/');
    }




    /**
     * Print html format
     *
     * @param array $files
     * @param string $blank
     * @return string
    */
    protected function renderTemplate(array $files, string $blank): string
    {
        $html = [];

        foreach ($files as $filename) {
            $html[] = sprintf($blank, $this->generatePath($filename));
        }

        return join("\n", $html);
    }
}


/*
echo assets('css/app.css');
echo assets('js/app.js');
echo "<img src=". assets('uploads/photo.png') . " alt='photo.png'>";
styles()
scripts()
*/