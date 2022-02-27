<?php
namespace Laventure\Component\Console\Input\Contract;


/**
 * @InputCommonInterface
*/
interface InputStateInterface
{
    const REQUIRED = 1;
    const OPTIONAL = 2;
    const IS_ARRAY = 3;


    /**
     * @param $mode
     * @return mixed
    */
    public function withMode($mode);

}