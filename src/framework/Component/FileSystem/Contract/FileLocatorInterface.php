<?php
namespace Laventure\Component\FileSystem\Contract;


/**
 * @FileLocatorInterface
*/
interface FileLocatorInterface
{
    /**
     * @param string $path
     * @return mixed
    */
    public function locate(string $path);
}