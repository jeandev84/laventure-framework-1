<?php
namespace Laventure\Foundation\Console\Storage;



use Laventure\Foundation\Console\Commands\Console\MakeCommand;
use Laventure\Foundation\Console\Commands\Database\DatabaseCreateCommand;
use Laventure\Foundation\Console\Commands\Database\DatabaseDropCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationGenerateCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationInstallCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationMigrateCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationResetCommand;
use Laventure\Foundation\Console\Commands\Database\Migration\MigrationRollbackCommand;
use Laventure\Foundation\Console\Commands\Database\ORM\MakeEntityCommand;
use Laventure\Foundation\Console\Commands\Database\ORM\MakeModelCommand;
use Laventure\Foundation\Console\Commands\Env\GenerateKeyCommand;
use Laventure\Foundation\Console\Commands\Routing\MakeControllerCommand;
use Laventure\Foundation\Console\Commands\Routing\MakeResourceCommand;
use Laventure\Foundation\Console\Commands\Server\ServerRunnerCommand;
use Laventure\Foundation\Console\Commands\Server\ServerStarterCommand;
use Laventure\Foundation\Console\Commands\Server\ServerStopperCommand;

/**
 * @CommandStack
*/
class CommandStack
{


    /**
     * @return string[]
    */
    public static function getDefaultCommands(): array
    {
        return [
            GenerateKeyCommand::class,
            ServerRunnerCommand::class,
            ServerStarterCommand::class,
            ServerStopperCommand::class,
            DatabaseCreateCommand::class,
            DatabaseDropCommand::class,
            MigrationGenerateCommand::class,
            MigrationInstallCommand::class,
            MigrationMigrateCommand::class,
            MigrationRollbackCommand::class,
            MigrationResetCommand::class,
            MakeCommand::class,
            MakeControllerCommand::class,
            MakeResourceCommand::class,
            MakeEntityCommand::class,
            MakeModelCommand::class,
        ];
    }
}