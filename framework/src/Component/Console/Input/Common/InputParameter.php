<?php
namespace Laventure\Component\Console\Input\Common;

use InvalidArgumentException;
use Laventure\Component\Console\Input\Contract\InputStateInterface;


/**
 * @InputParameter
*/
abstract class InputParameter implements InputStateInterface
{


    /**
     * @var string
    */
    protected $name;



    /**
     * @var int|void
     */
    protected $mode;



    /**
     * @var string
    */
    protected $description;



    /**
     * @var string
    */
    protected $default;





    /**
     * InputOption constructor.
     * @param string $name
     * @param int|null $mode
     * @param string|null $description
     * @param string|null $default
    */
    public function __construct(string $name, int $mode = null, string $description = null, string $default = null)
    {
        $this->withName($name)
             ->withMode($mode)
             ->withDescription($description)
             ->withDefault($default);
    }

    /**
     * @param $name
     * @return $this
    */
    public function withName($name): self
    {
        $this->name = $name;

        return $this;
    }




    /**
     * @return string
    */
    public function getName(): string
    {
        return $this->name;
    }



    /**
     * @param $description
     * @return $this
    */
    public function withDescription($description): self
    {
        $this->description = $description;

        return $this;
    }




    /**
     * @return string
    */
    public function getDescription(): string
    {
        return $this->description;
    }



    /**
     * @inheritDoc
    */
    public function withMode($mode): self
    {
        if (is_null($mode)) {
            $mode = self::OPTIONAL;
        }elseif (\in_array($mode, $this->getAvailableModes())) {
            throw new InvalidArgumentException(
                sprintf('Argument mode "%s" is not valid.', $mode)
            );
        }

        $this->mode = $mode;

        return $this;
    }




    /**
     * @return int|void
    */
    public function getMode()
    {
        return $this->mode;
    }



    /**
     * @param $default
     * @return $this
    */
    public function withDefault($default): self
    {
        $this->default = $default;

        return $this;
    }



    /**
     * @return string
    */
    public function getDefault(): string
    {
        return $this->default;
    }




    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->mode === self::OPTIONAL;
    }



    /**
     * @return bool
    */
    public function isRequired(): bool
    {
        return $this->mode === self::REQUIRED;
    }



    /**
     * @return bool
    */
    public function isArray(): bool
    {
        return $this->mode === self::IS_ARRAY;
    }





    /**
     * @return array
    */
    protected function getAvailableModes(): array
    {
         return [self::REQUIRED, self::IS_ARRAY, self::OPTIONAL];
    }
}