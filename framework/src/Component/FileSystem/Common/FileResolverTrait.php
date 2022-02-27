<?php
namespace Laventure\Component\FileSystem\Common;

/**
 * @FileResolverTrait
*/
trait FileResolverTrait
{
    /**
     * Resolve path
     *
     * @param string $path
     * @return string
    */
    public function resolvePath(string $path): string
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, trim($path, '\\/'));
    }




    /**
     * @param string $path
     * @return string
    */
    public function resolveResource(string $path): string
    {
        return rtrim(realpath($path), '\\/');
    }
}