<?php
namespace Laventure\Component\Console\Input;


use Laventure\Component\Console\Input\Common\InputParameter;



/**
 * @InputArgument
*/
class InputArgument extends InputParameter
{

    /**
     * InputArgument constructor.
     * @param string $name
     * @param int|null $mode
     * @param string|null $description
     * @param string|null $default
    */
    public function __construct(string $name, int $mode = null, string $description = null, string $default = null)
    {
            $this->withName($name)
                 ->withMode($mode)
                 ->withDescription($description)
                 ->withDefault($default);
    }
}