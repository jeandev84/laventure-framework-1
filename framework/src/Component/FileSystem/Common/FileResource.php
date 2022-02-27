<?php
namespace Laventure\Component\FileSystem\Common;

use Laventure\Component\FileSystem\Contract\FileResourceInterface;


/**
 * @FileResource
*/
abstract class FileResource implements FileResourceInterface
{


    use FileResolverTrait;



    /**
     * @var
    */
    protected $resource;




    /**
     * @param string|null $resource
     */
    public function __construct(string $resource)
    {
        if ($resource) {
            $this->resource($resource);
        }
    }



    /**
     * @param string $path
     * @return void
    */
    public function resource(string $path)
    {
        $this->resource = $this->resolveResource($path);
    }



    /**
     * @return mixed
    */
    public function getResource()
    {
        return $this->resource;
    }
}