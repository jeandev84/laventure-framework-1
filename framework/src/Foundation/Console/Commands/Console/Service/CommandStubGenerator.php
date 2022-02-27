<?php
namespace Laventure\Foundation\Console\Commands\Console\Service;

use Laventure\Component\Console\Command\Exception\CommandException;
use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Foundation\Console\Commands\Console\Exception\CommandStubGeneratorException;
use Laventure\Foundation\Service\Generator\StubGenerator;


/**
 * @CommandStubGenerator
*/
class CommandStubGenerator extends StubGenerator
{

        /**
         * @return string
        */
        protected function getStubPath(): string
        {
            return __DIR__ . '/stubs';
        }


    /**
     * @param string $commandName
     * @return string
     * @throws CommandStubGeneratorException
     * @throws FileWriterException
     * @throws CommandException
     */
        public function generateCommand(string $commandName): string
        {
            if (stripos($commandName, ':') === false) {
                throw new CommandStubGeneratorException('Invalid command name. (reference method '. __METHOD__.')');
            }

            $parts = explode(':', $commandName);
            $commandClass = end($parts);
            $prefix = '';

            if (count($parts) === 3) {
                $prefix = $parts[1];
            }

            $commandClass = $this->toCamelCaseCommandClass($commandClass);
            $prefix       = $this->toCamelCaseCommandClass($prefix);

            $commandClass = ucfirst($prefix) . ucfirst($commandClass).'Command';

             $stub = $this->generateStub('command', [
                'CommandClass' => $commandClass,
                'CommandNamespace' => 'App\\Console\\Command',
                'commandName' => $commandName,
                'commandDescription' => 'some description of command ...'
             ]);


             $targetPath = $this->resolvedPath(sprintf('app/Console/Command/%s.php', $commandClass));

             if ($this->fileSystem->exists($targetPath)) {
                throw new CommandException(sprintf('Command (%s) already generated!', $commandClass));
             }

             $this->dumpStub($targetPath, $stub);

             return sprintf('Command %s has been generated successfully to %s', $commandName, $targetPath);
        }



       /**
        * @param $input
        * @return string
       */
       protected function toCamelCaseCommandClass($input): string
       {
           return ucfirst(str_replace(['-', '_'], '', ucwords($input, "-\\_")));
       }
}