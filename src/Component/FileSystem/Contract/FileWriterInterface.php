<?php
namespace Laventure\Component\FileSystem\Contract;

/**
 * @FileWriterInterface
*/
interface FileWriterInterface
{
    /**
     * Write content into the file
     *
     * @param $filename
     * @param $content
     * @return false|int
    */
    public function write($filename, $content);
}