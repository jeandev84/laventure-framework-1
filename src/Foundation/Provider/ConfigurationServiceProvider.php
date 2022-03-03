<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Config\Config;
use Laventure\Component\Config\Loaders\ArrayLoader;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @ConfigurationServiceProvider
*/
class ConfigurationServiceProvider extends ServiceProvider
{

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->singleton(Config::class, function (FileSystem $fs) {

            $config = new Config();

            $config->load([
                $this->loadArrayResource($fs), // array loader
                // json loader
                // xml loader
                //..
            ]);

            return $config;
        });
    }



    /**
     * @param FileSystem $fs
     * @return ArrayLoader
     */
    protected function loadArrayResource(FileSystem $fs): ArrayLoader
    {
        $data = $fs->loadResourcesByName('/config/params/*.php');

        return new ArrayLoader($data);
    }


    protected function loadJsonResource()
    {
        //
    }


    protected function loadXmResource()
    {
        //
    }



    protected function loadPackagesByNames()
    {

    }
}