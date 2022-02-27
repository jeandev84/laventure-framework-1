<?php
namespace Laventure\Foundation\Templating\Contract;


use Laventure\Component\Templating\Contract\ViewFactoryInterface;


/**
 * @ViewAdapterInterface
*/
interface ViewAdapterInterface extends ViewFactoryInterface
{

    /**
     * @return mixed
    */
    public function getExtension();

}