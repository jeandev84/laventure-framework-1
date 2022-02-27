<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Config\Config;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Templating\Asset;


/**
 * @AssetServiceProvider
*/
class AssetServiceProvider extends ServiceProvider
{

    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->singleton(Asset::class, function (Config $config) {

            /* $asset =  new Asset(env('APP_URL')); */
            $asset =  new Asset();

            // $asset->addStyles($config['asset']['css']);
            // $asset->addScripts($config['asset']['js']);
            $asset->addStyles([]);
            $asset->addScripts([]);

            return $asset;
        });
    }
}