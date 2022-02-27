<?php
namespace Laventure\Foundation\Server;


use Laventure\Component\FileSystem\Exception\FileReaderException;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @ServerLocalReader
*/
class ServerLocalReader
{

    /**
     * @var string
    */
    protected $serverFile = 'bin/.webserver_cid';



    /**
     * @param FileSystem $fs
     * @return void
     * @throws FileReaderException
    */
    public function run(FileSystem $fs)
    {
        if ($fs->exists($this->serverFile)) {
            $host =  $fs->read($this->serverFile);
            $host = str_replace("\n", '', trim($host));
            exec(sprintf('php -S %s -t public -d display_errors=1 server.php', $host));
        }
    }


    /*
    public function runOLD(FileSystem $fs)
    {
        if ($fs->exists($this->serverFile)) {
            $host =  $fs->read($this->serverFile);
            $host = str_replace("\n", '', trim($host));
            exec(sprintf('php -S %s -t public -d display_errors=1 server.php', $host));
            // system(sprintf('php -S %s -t public -d display_errors=1 > /dev/null', $host));
            // exec('kill -9 ' . getmypid());
        }else {
            // exec('killall -9 php');
        }
    }
    */
}