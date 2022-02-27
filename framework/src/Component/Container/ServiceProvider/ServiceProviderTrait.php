<?php
namespace Laventure\Component\Container\ServiceProvider;


use Laventure\Component\Container\Container;
use Laventure\Component\Container\ContainerInterface;


/**
 * Class ServiceProviderTrait
 * @package Laventure\Component\Container\Common
 */
trait ServiceProviderTrait
{

    /**
     * @var Container
     */
    public $app;




    /**
     * @param Container $app
     */
    public function setContainer(Container $app)
    {
        $this->app = $app;
    }



    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->app;
    }
}