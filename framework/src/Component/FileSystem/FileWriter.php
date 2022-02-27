<?php
namespace Laventure\Component\FileSystem;


use Exception;
use Laventure\Component\FileSystem\Contract\FileWriterInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;


/**
 * @FileWriter
*/
class FileWriter extends FileLocator implements FileWriterInterface
{

    /**
     * Write content into the file
     *
     * @param $filename
     * @param $content
     * @return false|int
     * @throws FileWriterException
     */
    public function write($filename, $content): bool
    {
        try {
            return file_put_contents($this->locate($filename), $content.PHP_EOL, FILE_APPEND | LOCK_EX);

        } catch (Exception $e) {

            throw new FileWriterException($e->getMessage(), $e->getCode());
        }
    }




    /**
     * @param $filename
     * @param $content
     * @return void
    */
    public function writeForce($filename, $content)
    {
         $file = fopen($this->locate($filename), "a");
         fwrite($file, $content.PHP_EOL);
         fclose($file);
    }

}