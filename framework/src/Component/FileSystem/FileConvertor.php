<?php
namespace Laventure\Component\FileSystem;



use Laventure\Component\FileSystem\Contract\FileConvertorInterface;

/**
 * @FileConvertor
*/
class FileConvertor extends FileLocator implements FileConvertorInterface
{
    /**
     * @inheritDoc
    */
    public function convertToArray(string $path)
    {
        return file($this->locate($path), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}