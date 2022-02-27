<?php
namespace Laventure\Foundation\Console\Commands\Env;

use Laventure\Component\Console\Command\Command;
use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Output\Contract\OutputInterface;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @GenerateKeyCommand
*/
class GenerateKeyCommand extends Command
{

       /**
        * @var string
       */
       protected $name = 'generate:key';




       /**
        * @var string
       */
       protected $description = 'Generate a secret application key';




       /**
        * @var FileSystem
       */
       protected $fileSystem;





       /**
        * @param FileSystem $fileSystem
        * @param string|null $name
       */
       public function __construct(FileSystem $fileSystem, string $name = null)
       {
           parent::__construct($name);
           $this->fileSystem = $fileSystem;
       }


       /**
        * @param InputInterface $input
        * @param OutputInterface $output
        * @return int
        * @throws FileWriterException
       */
       protected function execute(InputInterface $input, OutputInterface $output): int
       {
            $hash = md5('Laventure'. uniqid(rand(), true));
            $secret = sprintf('APP_SECRET=%s', $hash);

            $oldContent = $this->fileSystem->read('.env');
            $newContent = preg_replace('/APP_SECRET=(.*)/', $secret, $oldContent);

            $this->fileSystem->remove('.env');
            $this->fileSystem->write('.env', $newContent);


            $output->writeln("New secret key '{$secret}' has been created in .env");

            return Command::SUCCESS;
       }
}