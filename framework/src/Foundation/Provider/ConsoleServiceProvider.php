<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Console\Command\Command;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Console\Console;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @ConsoleServiceProvider
*/
class ConsoleServiceProvider extends ServiceProvider
{


    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Console::class, function () {
              return new Console();
         });
    }
}