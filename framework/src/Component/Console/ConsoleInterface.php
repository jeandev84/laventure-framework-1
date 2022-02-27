<?php
namespace Laventure\Component\Console;


use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;

/**
 * @ConsoleInterface
*/
interface ConsoleInterface
{


    /**
     * @return string
    */
    public function name(): string;



    /**
     * @return string
    */
    public function version(): string;



    /**
     * @return string
    */
    public function helpCommand(): string;




    /**
     * @return mixed
    */
    public function listCommand();




    /**
     * @return string
    */
    public function description(): string;




    /**
     * Determine if the given input match config
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
    */
    public function run(InputInterface $input, OutputInterface $output);

}