<?php
namespace Laventure\Component\FileSystem;

use Laventure\Component\FileSystem\Contract\FileScannerInterface;



/**
 * @FileScanner
*/
class FileScanner extends FileLocator implements FileScannerInterface
{
     public function scan($pattern)
     {
         return scandir($this->locate($pattern));
     }
}