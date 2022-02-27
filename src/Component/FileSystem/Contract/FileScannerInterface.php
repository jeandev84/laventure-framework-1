<?php
namespace Laventure\Component\FileSystem\Contract;


/**
 * @FileScannerInterface
*/
interface FileScannerInterface
{
    public function scan($pattern);
}