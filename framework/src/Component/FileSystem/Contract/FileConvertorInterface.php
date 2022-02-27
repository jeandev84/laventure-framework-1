<?php
namespace Laventure\Component\FileSystem\Contract;


/**
 * @FileConvertorInterface
*/
interface FileConvertorInterface
{
      /**
       * @param string $path
       * @return mixed
      */
      public function convertToArray(string $path);
}