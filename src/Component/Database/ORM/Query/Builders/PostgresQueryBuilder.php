<?php
namespace Laventure\Component\Database\ORM\Query\Builders;


use Laventure\Component\Database\ORM\Query\QueryBuilder;

/**
 * @PostgresQueryBuilder
*/
class PostgresQueryBuilder extends QueryBuilder
{

    // todo ONLY Postgres to refactoring later
    public function showColumns($table)
    {
        $this->em->exec($this->createSQLFunctionShowColumns());
        $fields =  $this->em->createNativeQuery($this->sqlShowColumnsMoreSure($table))->getResult();

        $columns = [];

        foreach ($fields as $field) {
            $columns[] = $field->attributes['column_name'];
        }

        // dd($columns);

        return $columns;
    }


    /**
     * @return string
     */
    protected function createSQLFunctionShowColumns(): string
    {
        return "create or replace function describe_table(tbl_name text) returns table(column_name   
                    varchar, data_type varchar,character_maximum_length int) as $$
                    select column_name, data_type, character_maximum_length
                    from INFORMATION_SCHEMA.COLUMNS where table_name = $1;
                    $$
                    language 'sql';
            ";
    }


    protected function sqlShowColumnsMoreSure($table)
    {
        return sprintf("select  *  from describe_table('%s')", $table);
    }
}