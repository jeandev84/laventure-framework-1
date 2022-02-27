<?php
namespace Laventure\Component\Container;


/**
 * @see ContainerAwareTrait
 *
 * @package Laventure\Component\Container
*/
trait ContainerAwareTrait
{

    /**
     * @var ContainerInterface
    */
    protected $container;




    /**
     * @param ContainerInterface $container
     * @return mixed
    */
    public function setContainer(ContainerInterface $container)
    {
           $this->container = $container;
    }
}