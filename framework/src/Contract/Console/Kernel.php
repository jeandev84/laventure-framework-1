<?php
namespace Laventure\Contract\Console;


use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;


/**
 * @Kernel
*/
interface Kernel
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
    */
    public function handle(InputInterface $input, OutputInterface $output);



    /**
     * @param InputInterface $input
     * @param mixed $status
     * @return mixed
    */
    public function terminate(InputInterface $input, $status);
}