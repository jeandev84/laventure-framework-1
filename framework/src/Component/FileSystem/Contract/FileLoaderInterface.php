<?php
namespace Laventure\Component\FileSystem\Contract;


/**
 * @FileLoaderInterface
*/
interface FileLoaderInterface
{
    /**
     * Load file
     *
     * @param string $path
     * @return mixed
    */
    public function load(string $path);
}