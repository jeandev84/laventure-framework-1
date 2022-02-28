<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Templating\Contract\RendererInterface;
use Laventure\Component\Templating\Renderer;


/**
 * @ViewServiceProvider
*/
class ViewServiceProvider extends ServiceProvider
{

    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton('view', function () {
              return new Renderer(sprintf('%s/templates', $this->app['path']));
         });

         $this->app->singleton(Renderer::class, function () {
            return $this->app['view'];
         });

         $this->app->singleton(RendererInterface::class, function () {
            return $this->app['view'];
         });
    }
}