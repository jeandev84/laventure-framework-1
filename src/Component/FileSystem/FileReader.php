<?php
namespace Laventure\Component\FileSystem;


use Laventure\Component\FileSystem\Common\FileSystemTrait;
use Laventure\Component\FileSystem\Contract\FileReaderInterface;
use Laventure\Component\FileSystem\Exception\FileReaderException;

/**
 * @FileReader
*/
class FileReader extends FileLocator implements FileReaderInterface
{
    /**
     * @param string $path
     * @return false|string
     * @throws FileReaderException
     */
    public function read(string $path)
    {
         $path = $this->locate($path);

         if (! is_readable($path)) {
              return false;
              // throw new FileReaderException("File {$path} is not readable.". __METHOD__);
         }

         return file_get_contents($path);
    }
}