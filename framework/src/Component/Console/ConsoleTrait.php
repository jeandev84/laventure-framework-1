<?php
namespace Laventure\Component\Console;



use Laventure\Component\Console\Command\Defaults\HelpCommand;
use Laventure\Component\Console\Command\Defaults\ListCommand;

/**
 * @ConsoleTrait
*/
trait ConsoleTrait
{

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Laventure Console';
    }


    /**
     * @return string
     */
    public function version(): string
    {
        return '1.0';
    }



    /**
     * @return string
     */
    public function helpCommand(): string
    {
        return 'help';
    }



    /**
     * @return string
    */
    public function listCommand(): string
    {
        return  'list';
    }


    /**
     * @return string
     */
    public function description(): string
    {
        return 'description console commands';
    }


    public function getAvailableCommands(array $commands)
    {
        // todo implements
    }
}