<?php
namespace Laventure\Component\Templating\Contract;


/**
 * @AssetInterface
*/
interface AssetInterface
{
    /**
     * Get css data
     *
     * @return array
     */
    public function getStyles(): array;


    /**
     * Get css data
     *
     * @return array
    */
    public function getScripts(): array;
}