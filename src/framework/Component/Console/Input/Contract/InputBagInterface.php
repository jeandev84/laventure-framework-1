<?php
namespace Laventure\Component\Console\Input\Contract;



/**
 * @InputBagInterface
*/
interface InputBagInterface
{
    /**
     * @param string $name
     * @return mixed
    */
    public function getArgument(string $name);




    /**
     * @return array
    */
    public function getArguments(): array;




    /**
     * @param string $name
     * @return mixed|null
    */
    public function getOption(string $name);




    /**
     * @return array
    */
    public function getOptions(): array;
}