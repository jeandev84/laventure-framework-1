<?php
namespace Laventure\Component\Database\Builder\SQL;


use Laventure\Component\Database\Builder\Common\SqlBuilder;


/**
 * @InsertBuilder
*/
class InsertBuilder extends SqlBuilder
{

    /**
     * @var array
    */
    protected $attributes = [];


    /**
     * @param array $attributes
     * @param string $table
    */
    public function __construct(array $attributes, string $table)
    {
         $this->setParameters($attributes);
         $this->attributes = $attributes;
         $this->table = $table;
    }



    /**
     * @inheritDoc
    */
    protected function buildPrependSQL(): string
    {
         return sprintf("INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            $this->getAttributeToInline(),
            $this->getBindParametersToInline()
         );
    }



    /**
     * @return array
    */
    protected function getAttributes(): array
    {
        return array_keys($this->attributes);
    }



    /**
     * @return string
    */
    protected function getAttributeToInline(): string
    {
        /* return '`' . implode('`, `', $this->getAttributes()) . '`'; */
        return  implode(', ', $this->getAttributes());
    }


    /**
     * @return string
    */
    protected function getBindParametersToInline(): string
    {
        return  ":" . implode(", :", $this->getAttributes());
    }

}