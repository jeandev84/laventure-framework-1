<?php
namespace Laventure\Component\Dotenv;


use Exception;

/**
 * @Dotenv
 *
 * todo refactoring !!!
*/
class Dotenv
{

    /**
     * @var Dotenv
     */
    protected static $instance;



    /**
     * @var string
     */
    protected $root;



    /**
     * @var Env
    */
    protected $env;



    /**
     * Dotenv constructor.
     *
     * @param string $root
     */
    public function __construct(string $root)
    {
          $this->root = $root;
          $this->env  = new Env();
    }



    /**
     * @param string $resource
     * @return Dotenv
    */
    public static function create(string $resource): Dotenv
    {
        if (! static::$instance) {
            static::$instance = new static($resource);
        }

        return static::$instance;
    }



    /**
     * @param string $filename
     * @return bool
     * @throws Exception
     */
    public function load(string $filename = '.env'): bool
    {
        if ($environs = $this->loadEnvironments($filename)) {
            foreach ($environs as $environ) {
                $this->env->put($environ);
            }

            return true;
        }

        return false;
    }



    /**
     * @param string $filename
     * @return array
     * @throws Exception
    */
    public function loadEnvironments(string $filename): array
    {
        $filename = $this->root . DIRECTORY_SEPARATOR. $filename;

        if(! file_exists($filename)) {
            $content = file_get_contents(__DIR__ . '/stub/env.stub');
            $filename = $this->replaceStub($content);
        }

        return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }



    /**
     * @param string $content
     * @return string
    */
    protected function replaceStub(string $content): string
    {
        $filename = $this->root. '/.env';
        touch($filename);
        file_put_contents($filename, $content);
        return $filename;
    }
}