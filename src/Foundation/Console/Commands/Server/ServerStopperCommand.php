<?php
namespace Laventure\Foundation\Console\Commands\Server;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Foundation\Console\Commands\Server\Common\AbstractServerCommand;



/**
 * @ServerStopperCommand
*/
class ServerStopperCommand extends AbstractServerCommand
{

    /**
     * @var string
    */
    protected $name = 'server:stop';



    /**
     * @var string
    */
    protected $description = 'Stop server.';



    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->fileSystem->exists('bin/127.0.0.1:8000')) {
            $this->fileSystem->remove('bin/127.0.0.1:8000');
            $output->writeln("Server stopped.");
        }

        return Command::SUCCESS;
    }
}