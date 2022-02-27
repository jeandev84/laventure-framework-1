<?php
namespace Laventure\Foundation\Console\Commands\Server;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Foundation\Console\Commands\Server\Common\AbstractServerCommand;



/**
 * @ServerStarterCommand
*/
class ServerStarterCommand extends AbstractServerCommand
{

    /**
     * @var string
    */
    protected $name = 'server:start';



    /**
     * @var string
    */
    protected $description = 'start server';


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
    */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /*
        if (! $this->fileSystem->exists('bin/127.0.0.1:8000')) {
            $this->fileSystem->write('bin/127.0.0.1:8000', '127.0.0.1:8000');
        }

        // reference to method terminate() Kernel console
        $output->writeln("Server started.");
        */

        /*
        dump("Server started!\n");
        $cmd = 'php -S localhost:8888 -t public -d display_errors=1';
        exec($cmd, $output, $return_var);
        dump($return_var);
        */

        $output->writeln("Server started.");

        if (! $this->fileSystem->exists('bin/.webserver_pid')) {
            $this->fileSystem->write('bin/.webserver_pid', '127.0.0.1:8000');
        }

        return Command::SUCCESS;
    }
}