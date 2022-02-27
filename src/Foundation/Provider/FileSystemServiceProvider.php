<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @FileSystemServiceProvider
*/
class FileSystemServiceProvider extends ServiceProvider
{

    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->alias('fileSystem', FileSystem::class);

        $this->app->singleton(FileSystem::class, function () {
            return new FileSystem($this->app['path']);
        });
    }
}