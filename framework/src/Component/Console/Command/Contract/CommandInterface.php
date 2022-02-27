<?php
namespace Laventure\Component\Console\Command\Contract;


use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;


/**
 * @CommandInterface
*/
interface CommandInterface
{

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
    */
    public function execute(InputInterface $input, OutputInterface $output);
}