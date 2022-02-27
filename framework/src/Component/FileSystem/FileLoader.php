<?php
namespace Laventure\Component\FileSystem;


use Laventure\Component\FileSystem\Contract\FileLoaderInterface;



/**
 * @FileLoader
*/
class FileLoader extends FileLocator implements FileLoaderInterface
{

    /**
     * @param string $path
     * @return mixed
    */
    public function load(string $path)
    {
         return require $this->locate($path);
    }



    /**
     * @param string $pattern
     * @return void
     */
    public function loadResources(string $pattern)
    {
        $files = $this->locateResources($pattern);

        foreach ($files as $file) {
            require_once $file;
        }
    }


    /**
     * @param string $pattern
     * @return array
    */
    public function loadResourceFileNames(string $pattern): array
    {
        $files = $this->locateResources($pattern);

        $data = [];

        foreach ($files as $file) {
            $data[] = pathinfo($file)['filename'];
        }

        return $data;
    }



    /**
     * @param string $pattern
     * @return array
    */
    public function loadResourcesByName(string $pattern): array
    {
        $files = $this->locateResources($pattern);

        $data = [];

        foreach ($files as $file) {
            $data[pathinfo($file)['filename']] = $file;
        }

        return $data;
    }
}