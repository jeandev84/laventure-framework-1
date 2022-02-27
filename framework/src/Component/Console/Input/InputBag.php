<?php
namespace Laventure\Component\Console\Input;


use Laventure\Component\Console\Input\Contract\InputBagInterface;



/**
 * @InputBag
*/
class InputBag implements InputBagInterface
{


    /**
     * @var array
    */
    private $arguments = [];


    /**
     * @var array
    */
    private $options = [];




    public function __construct()
    {
    }

    /**
     * @param InputArgument $argument
     * @return $this
    */
    public function addArgument(InputArgument $argument): InputBag
    {
        $this->arguments[$argument->getName()] = $argument;

        return $this;
    }


    /**
     * @param string $name
     * @return bool
    */
    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->arguments);
    }


    /**
     * @param string $name
     * @return mixed
    */
    public function getArgument(string $name)
    {
         return $this->arguments[$name];
    }


    /**
     * @return array
    */
    public function getArguments(): array
    {
        return $this->arguments;
    }



    /**
     * @param InputOption $option
    */
    public function addOption(InputOption $option)
    {
        $this->options[$option->getName()] = $option;
    }



    /**
     * @param string $name
     * @return bool
    */
    public function hasOption(string $name): bool
    {
        return \array_key_exists($name, $this->options);
    }




    /**
     * @param string $name
     * @return mixed|null
    */
    public function getOption(string $name)
    {
        return $this->options[$name] ?? null;
    }




    /**
     * @return array
    */
    public function getOptions(): array
    {
        return $this->options;
    }

}