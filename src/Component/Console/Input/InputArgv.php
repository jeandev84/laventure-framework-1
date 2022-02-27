<?php
namespace Laventure\Component\Console\Input;


use Laventure\Component\Console\Input\Contract\InputInterface;
use Laventure\Component\Console\Input\Exception\InputArgumentException;
use Laventure\Component\Console\Input\Exception\InputOptionException;


/**
 * todo later more advance InputArgv with required or optional parameter
 *
 * @InputArgv
*/
abstract class InputArgv implements InputInterface
{

    /**
     * @var array
    */
    protected $tokens = [];




    /**
     * @var array
    */
    protected $commands = [];




    /**
     * @var array
    */
    protected $flags = [];




    /**
     * parses arguments
     *
     * @var array
    */
    protected $arguments = [];





    /**
     * @var array
    */
    protected $shortcuts = [];




    /**
     * parses options
     *
     * @var array
    */
    protected $options = [];





    /**
     * @var InputArgument[]
    */
    protected $argumentBag = [];




    /**
     * @var InputOption[]
    */
    protected $optionBag = [];




    /**
     * @var string
    */
    protected $firstArgument;




    /**
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;

        $this->prepareTokens($tokens);
    }




    /**
     * @param array $tokens
     * @return mixed
    */
    abstract public function parseTokens(array $tokens);




    /**
     * @return array
    */
    public function getTokens(): array
    {
        return $this->tokens;
    }


    
    /**
     * @param string $command
     * @return $this
    */
    public function withCommand(string $command): self
    {
          $this->commands[] = $command;
          
          return $this;
    }




    /**
     * @param array $commands
     * @return $this
    */
    public function withCommands(array $commands): self
    {
        $this->commands = array_merge($this->commands, $commands);

        return $this;
    }




    /**
     * @return array
    */
    public function getCommands(): array
    {
        return $this->commands;
    }
    
    


    /**
     * @param $argument
     * @return void
    */
    public function setFirstArgument($argument)
    {
         $this->firstArgument = $argument;

         $this->withCommand($argument);
    }

    
    


    /**
     * @inheritDoc
    */
    public function getFirstArgument()
    {
        return $this->firstArgument;
    }




    /**
     * @param $name
     * @param $value
     * @return self
    */
    public function withArgument($name, $value = null): self
    {
        if (! $value) {
            $this->arguments[] = $name;
        }else{
            $this->arguments[$name] = $value;
        }

        return $this;
    }




    /**
     * @param array $arguments
     * @return self
     */
    public function withArguments(array $arguments): self
    {
        $this->arguments = array_merge($this->arguments, $arguments);

        return $this;
    }




    /**
     * @param string $name
     * @return bool
    */
    public function hasArgument(string $name): bool
    {
         if (! empty($this->argumentBag)) {
              return isset($this->argumentBag[$name]);
         }

         return isset($this->arguments[$name]);
    }




    /**
     * @param string|null $name
     * @return mixed|string
     * @throws InputArgumentException
    */
    public function getArgument($name = null)
    {
         if (! $name) {
             return $this->getDefaultArgument();
         }

         if (! $this->hasArgument($name)) {
             throw new InputArgumentException("Invalid argument name '{$name}'");
         }

         return $this->arguments[$name] ?? "";
    }



    /**
     * @return mixed|string
    */
    protected function getDefaultArgument()
    {
         return $this->arguments[0] ?? "";
    }




    /**
     * @return array
    */
    public function getArguments(): array
    {
        return $this->arguments;
    }



    /**
     * @param $name
     * @param $value
     * @return $this
    */
    public function withShortcuts($name, $value): self
    {
         $this->shortcuts[$name] = $value;

         $this->withOption($name, $value);

         return $this;
    }



    /**
     * @return array
    */
    public function getShortcuts(): array
    {
        return $this->shortcuts;
    }



    /**
     * @param $name
     * @param $value
     * @return self
    */
    public function withOption($name, $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }



    /**
     * @param string $name
     * @return bool
    */
    public function hasOption(string $name): bool
    {
        if (! empty($this->optionBag)) {
            return isset($this->optionBag[$name]);
        }

        return isset($this->options[$name]);
    }




    /**
     * @param string|null $name
     * @return string
     */
    public function getOption($name): string
    {
        return $this->options[$name] ?? "";
    }



    /**
     * @return array
    */
    public function getOptions(): array
    {
         return $this->options;
    }




    /**
     * @param string $name
     * @param $value
     * @return $this
    */
    public function withFlag(string $name, $value): self
    {
         $this->flags[$name] = $value;

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function hasFlag(string $name)
    {
        return isset($this->flags[$name]);
    }





    /**
     * @inheritDoc
    */
    public function getFlags()
    {
        return $this->flags;
    }




    /**
     * @inheritDoc
    */
    public function withArgumentsBag(array $arguments)
    {
        foreach ($arguments as $argument) {
            $this->withArgumentBag($argument);
        }

        return $this;
    }



    /**
     * @inheritDoc
    */
    public function withOptionsBag(array $options)
    {
        foreach ($options as $option) {
            $this->withOptionBag($option);
        }

        return $this;
    }




    /**
     * @param InputArgument $argument
     * @return $this
    */
    public function withArgumentBag(InputArgument $argument): self
    {
        $this->argumentBag[$argument->getName()] = $argument;

        return $this;
    }



    /**
     * @param InputOption $option
     * @return $this
    */
    public function withOptionBag(InputOption $option): self
    {
         $this->optionBag[$option->getName()] = $option;

         return $this;
    }




    /**
     * @return array[]
    */
    public function traceParses(): array
    {
        return [
           'commands'  => $this->getCommands(),
           'arguments' => $this->getArguments(),
           'options'   => $this->getOptions(),
           'shortcuts' => $this->getShortcuts(),
           'flags'     => $this->getFlags()
        ];
    }




    /**
     * @param array $tokens
     * @return void
    */
    private function prepareTokens(array $tokens)
    {
        array_shift($tokens);

        if (isset($tokens[0])) {
            $this->setFirstArgument($tokens[0]);
        }

        array_shift($tokens);

        $this->parseTokens($tokens);
    }

}