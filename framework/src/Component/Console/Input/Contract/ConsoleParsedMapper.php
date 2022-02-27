<?php
namespace Laventure\Component\Console\Input\Contract;


/**
 * @ConsoleParsedMapper
*/
interface ConsoleParsedMapper
{

    /**
     * @param $parses
     * @return mixed
    */
    public function map($parses);
}