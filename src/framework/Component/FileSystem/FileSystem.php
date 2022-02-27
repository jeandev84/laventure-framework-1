<?php
namespace Laventure\Component\FileSystem;



/**
 * @FileSystem
*/
class FileSystem
{

    /**
     * @var FileSystemFactory
    */
    public $factory;



    /**
     * @param string|null $resource
    */
    public function __construct(string $resource = null)
    {
         $this->factory = $this->factory($resource);
    }



    /**
     * @param string $path
     * @return self
    */
    public function resource(string $path): self
    {
         $this->factory = $this->factory($path);

         return $this;
    }




    /**
     * @param string $path
     * @return string
    */
    public function locate(string $path): string
    {
        return $this->factory->locator->locate($path);
    }




    /**
     * @param string $path
     * @return array|false
    */
    public function asArray(string $path)
    {
        return $this->factory->convertor->convertToArray($path);
    }




    /**
     * @param string $path
     * @param $newContent
     * @return bool|int
     * @throws Exception\FileWriterException
    */
    public function regenerate(string $path, $newContent)
    {
         $this->remove($path);
         return $this->write($path, $newContent);
    }




    /**
     * @param string $path
     * @return mixed
     */
    public function load(string $path)
    {
        if (! $this->exists($path)) {
            return false;
        }

        return $this->factory->loader->load($path);
    }



    /**
     * @param string $pattern
     * @return void
    */
    public function loadResources(string $pattern)
    {
         $this->factory->loader->loadResources($pattern);
    }


    /**
     * @param string $pattern
     * @return array
    */
    public function loadResourcesByName(string $pattern): array
    {
        return $this->factory->loader->loadResourcesByName($pattern);
    }


    /**
     * @param $pattern
     * @return array
    */
    public function loadResourceFileNames($pattern): array
    {
        return $this->factory->loader->loadResourceFileNames($pattern);
    }



    /**
     * searches for all the path names matching pattern
     *
     * Example : resources('/config/*.php')
     *
     * @param string $pattern
     * @param int $flags
     * @return array|false
    */
    public function locateResources(string $pattern, int $flags = 0)
    {
          return $this->factory->locator->locateResources($pattern, $flags);
    }




    /**
     * @param string $path
     * @return false|string
    */
    public function realpath(string $path)
    {
        return realpath($this->locate($path));
    }



    /**
     * @param string $pattern
     * @return array|false
    */
    public function scan(string $pattern)
    {
        return scandir($this->locate($pattern));
    }




    /**
     * @param string $path
     * @return bool
    */
    public function exists(string $path): bool
    {
        return file_exists($this->locate($path));
    }





    /**
     * @param string $path
     * @return bool
    */
    public function has(string $path): bool
    {
        return \is_file($this->locate($path));
    }


    /**
     * Write content into the file
     *
     * @param $filename
     * @param $content
     * @return false|int
     * @throws Exception\FileWriterException
    */
    public function write($filename, $content): bool
    {
        return $this->factory->writer->write($filename, $content);
    }


    /**
     * read file content
     *
     * @param $filename
     * @return false|string
     * @throws Exception\FileReaderException
    */
    public function read($filename)
    {
        return $this->factory->reader->read($filename);
    }




    /**
     * uploading file
     *
     * @param $target
     * @param $filename
    */
    public function move($target, $filename)
    {
        move_uploaded_file($this->mkdir($target), $filename);
    }



    /**
     * @param $filename
     * @return array
     */
    public function info($filename): array
    {
        return pathinfo($this->locate($filename));
    }




    /**
     * Create directory
     *
     * @param string $path
     * @return bool
     */
    public function mkdir(string $path): bool
    {
        $path = $this->locate($path);

        if(! \is_dir($path)) {
            return @mkdir($path, 0777, true);
        }

        return $path;
    }




    /**
     * Create a file
     *
     * @param string $filename
     * @return bool
     */
    public function make(string $filename): bool
    {
        $dirname = dirname($this->locate($filename));

        if(! \is_dir($dirname)) {
            @mkdir($dirname, 0777, true);
        }

        return touch($this->locate($filename));
    }




    /**
     * @param string $filename
     * @param string $base64
     * @return false|int
     */
    public function dumpFile(string $filename, string $base64)
    {
        $this->make($filename);

        return $this->write($filename, base64_decode($base64, true));
    }



    /**
     * @param string $filename
     * @param array $replacements
     * @return string|string[]
     */
    public function replace(string $filename, array $replacements)
    {
        $replaces = array_keys($replacements);

        return str_replace($replaces, $replacements, $this->read($filename));
    }



    /**
     * copy file to other destination
     *
     * @param string $from
     * @param string $destination
     */
    public function copy(string $from, string $destination)
    {
        // TODO implements
    }





    /**
     * @param $filename
     * @return bool
    */
    public function remove($filename): bool
    {
        if(! $this->exists($filename)) {
            return false;
        }

        return unlink($this->locate($filename));
    }



    /**
     * Remove files
     *
     * @param string $pattern
    */
    public function removeResources(string $pattern)
    {
        if($resources = $this->resources($pattern)) {
            array_map("unlink", $resources);
        }
    }


    /**
     * @param string|null $path
     * @return FileSystemFactory
    */
    public function factory(string $path = null): FileSystemFactory
    {
       return $path ? new FileSystemFactory($path) : new FileSystemFactory();
    }




    /**
     * @param string $path
     * @return string
    */
    public function resolvePath(string $path): string
    {
        return $this->factory->locator->resolvePath($path);
    }
}