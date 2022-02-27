<?php
namespace Laventure\Component\FileSystem\Contract;

/**
 * @FileReaderInterface
*/
interface FileReaderInterface
{
     public function read(string $path);
}