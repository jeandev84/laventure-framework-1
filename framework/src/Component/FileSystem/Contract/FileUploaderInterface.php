<?php
namespace Laventure\Component\FileSystem\Contract;


/**
 * @FileUploaderInterface
*/
interface FileUploaderInterface
{
    /**
     * upload file
     *
     * @param $target
     * @param $filename
    */
    public function upload($target, $filename);
}