<?php
namespace Laventure\Foundation\Facade\Templating;


use Laventure\Component\Container\Facade\Facade;



/**
 * @Asset
*/
class Asset extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Laventure\Component\Templating\Asset::class;
    }
}