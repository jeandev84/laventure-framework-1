<?php
namespace Laventure\Component\FileSystem;


use Laventure\Component\FileSystem\Contract\FileUploaderInterface;



/**
 * @FileUploader
*/
class FileUploader implements FileUploaderInterface
{

    /**
     * @inheritDoc
    */
    public function upload($target, $filename): bool
    {
         return move_uploaded_file($target, $filename);
    }
}