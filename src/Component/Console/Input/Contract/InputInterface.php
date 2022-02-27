<?php
namespace Laventure\Component\Console\Input\Contract;


use Laventure\Component\Console\Input\InputArgument;

/**
 * @InputInterface
*/
interface InputInterface
{

    /**
     * This method return all parses tokens
     *
     * Example: php console test a=1 b=3 -c=foo --d=bar -m --n
     *
     * @return mixed
    */
    public function getTokens();



    /**
     * This method return first parsed argument
     *
     * Example: php console myFirstArgument
     *
     * @return mixed
    */
    public function getFirstArgument();




    /**
     * This  method implements setting arguments, options, flags ...
     *
     * @param array $tokens
     * @return mixed
    */
    public function parseTokens(array $tokens);





    /**
     * Store commands
     *
     * @return mixed
    */
    public function getCommands();





    /**
     * Get all arguments
     *
     * @return mixed
     */
    public function getArgument($name = null);




    /**
     * Get arguments
     *
     * @return mixed
     */
    public function getArguments();




    /**
     * Get all arguments
     *
     * @return mixed
     */
    public function getOption($name);




    /**
     * Get options
     *
     * @return mixed
     */
    public function getOptions();




    /**
     * Determine if has defined flag
     *
     * @param string $name
     * @return mixed
    */
    public function hasFlag(string $name);





    /**
     * Get flags
     *
     * @return mixed
    */
    public function getFlags();






    /**
     * @param InputArgument[] $arguments
     * @return mixed
    */
    public function withArgumentsBag(array $arguments);





    /**
     * @param array $options
     * @return mixed
    */
    public function withOptionsBag(array $options);

}