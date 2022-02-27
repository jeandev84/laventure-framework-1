<?php
namespace Laventure\Component\Database\Builder\SQL;


use Laventure\Component\Database\Builder\Common\SqlBuilder;


/**
 * @UpdateBuilder
*/
class UpdateBuilder extends SqlBuilder
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
         $this->attributes = array_keys($attributes);
         $this->table = $table;
    }



    /**
     * @inheritDoc
    */
    protected function buildPrependSQL(): string
    {
         return sprintf("UPDATE %s SET %s",
             $this->table,
             $this->buildColumnAssigns()
         );
    }



    /**
     * @return string
    */
    protected function buildColumnAssigns(): string
    {
        $fields = [];

        foreach ($this->attributes as $column) {
            $fields[] = sprintf("%s = :%s", $column, $column);

            /* array_push($fields, sprintf("%s = :%s", $column, $column)); */
        }

        return join(', ', $fields);
    }
}