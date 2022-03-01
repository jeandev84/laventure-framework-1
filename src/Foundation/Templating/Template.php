<?php
namespace Laventure\Foundation\Templating;



use Laventure\Component\FileSystem\FileSystem;

/**
 * @Template
*/
class Template
{


    /**
     * @var string[]
    */
    protected $params = [
        ':'        =>  ": ?>",
        '{'        =>  "<?= ",
        '}'        =>  ";?>",
        '@if'      =>  "<?php if",
        '@endif'   =>  "<?php endif; ?>",
        '@loop'     => "<?php foreach",
        '@endloop' =>  "<?php endforeach; ?>",
    ];




    /**
     * @var string
    */
    protected $cacheDir;



    /**
     * @param string $cacheDir
    */
    public function __construct(string $cacheDir)
    {
         $this->cacheDir = new FileSystem($cacheDir);
    }



    /**
     * @param string $template
     * @param string $content
     * @return false|string
    */
    public function make(string $template, string $content)
    {
        $content = str_replace(
            array_keys($this->params),
            array_values($this->params),
            $content
        );

        $templateCachePath = sprintf('%s/%s', $this->cacheDir, $template);

        if(! file_put_contents($templateCachePath, $content)) {
             return false;
        }

        return $templateCachePath;
    }




    /**
     * @param string $templateCachePath
     * @param array $variables
     * @return false|string
    */
    public function renderCache(string $templateCachePath, array $variables)
    {
          extract($variables, EXTR_SKIP);

          ob_start();
          @require $templateCachePath;
          return ob_get_clean();
    }

}