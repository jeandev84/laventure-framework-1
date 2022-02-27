<?php
namespace Laventure\Component\FileSystem;


use Laventure\Component\FileSystem\Common\FileResource;
use Laventure\Component\FileSystem\Contract\FileLocatorInterface;


/**
 * @FileLocator
*/
class FileLocator extends FileResource implements FileLocatorInterface
{

    /**
     * @param string $path
     * @return string
    */
    public function locate(string $path): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->resource, $this->resolvePath($path)]);
    }


    /**
     * @param string $pattern
     * @param int $flags
     * @return array|false
    */
    public function locateResources(string $pattern, int $flags = 0)
    {
        return glob($this->locate($pattern), $flags);
    }

}