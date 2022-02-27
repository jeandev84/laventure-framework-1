<?php
namespace Laventure\Component\Container;


/**
 * @ContainerAwareInterface
*/
interface ContainerAwareInterface
{

    /**
     * @param ContainerInterface $container
     * @return mixed
    */
    public function setContainer(ContainerInterface $container);



    /**
     * @return Container
    */
    public function getContainer(): ContainerInterface;
}