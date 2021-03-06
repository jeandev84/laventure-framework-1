<?php
namespace Laventure\Component\Database\Schema\Column;

use Exception;
use Laventure\Component\Database\Schema\Column\Contract\BluePrintColumnInterface;
use Laventure\Component\Database\Schema\Column\Exception\BluePrintColumnException;


/**
 * @BluePrintColumn
*/
abstract class BluePrintColumn implements BluePrintColumnInterface
{


     /**
      * @var string
     */
     public $table;




    /**
     * @var ColumnCollection
    */
    public $columns;





    /**
     * @var array
    */
    public $alteredColumns = [];




    /**
     * BluePrintColumn
    */
    public function __construct(string $table)
    {
        $this->columns = new ColumnCollection();
        $this->table   = $table;
    }




    /**
     * @param string $name
     * @param string $type
     * @param int $length
     * @param $default
     * @param bool $autoincrement
     * @return Column
    */
    public function add($name, $type, $length = 11, $default = null, $autoincrement = false): Column
    {
         $type = $length ? sprintf('%s(%s)', $type, $length) : $type;
         $default = $default ? sprintf('DEFAULT "%s"', $default) : 'NOT NULL';

         $column = new Column(
             compact('name', 'type', 'default')
         );

         if ($autoincrement) {
             $column->withParam('primaryKey', 'PRIMARY KEY');
             if (is_string($autoincrement)) {
                 $column->withParam('autoincrement', $autoincrement);
             }
         }

         return $this->columns->addColumn($name, $column);
    }




    /**
     * @param $name
     * @param int $length
     * @return Column
    */
    public function integer($name, int $length = 11): Column
    {
        return $this->add($name, 'INTEGER', $length);
    }



    /**
     * @param string $name
     * @param int $length
     * @return Column
    */
    public function string(string $name, int $length = 255): Column
    {
        return $this->add($name, 'VARCHAR', $length);
    }




    /**
     * @param $name
     * @return Column
    */
    public function text($name): Column
    {
        return $this->add($name, 'TEXT', null);
    }




    /**
     * @param $name
     * @return Column
    */
    public function datetime($name): Column
    {
        return $this->add($name, 'DATETIME', null);
    }




    /**
     * @return void
    */
    public function timestamps()
    {
        $this->datetime('created_at');
        $this->datetime('updated_at');
    }




    /**
     * @param bool $status
     * @return Column|void
     * @throws Exception
     */
    public function softDeletes(bool $status = false)
    {
        if($status) {
            return $this->boolean('deleted_at');
        }
    }



    /**
     * @return string
    */
    public function printColumns(): string
    {
        return (function () {

            if ($this->columns->isEmpty()) {
                throw new BluePrintColumnException("Cannot print empty column.". __METHOD__);
            }

            $sql = '';

            foreach ($this->columns->getColumns() as $column) {
                $values = $this->filterColumnValues($column);
                $sql .= implode(" ", $values) . ', ';
            }

            return strtolower(trim($sql, ', '));

        })();
    }





    /**
     * @param Column $column
     * @return array
    */
    protected function filterColumnValues(Column $column): array
    {
        $values = [];
        foreach ($column->getParamValues() as $value) {
            if ($value) {
                $values[] = $value;
            }
        }
        return $values;
    }




    /**
     * @param string $name
     * @return bool
    */
    public function hasColumn(string $name): bool
    {
         return  $this->columns->hasColumn($name);
    }




    /**
     * @param string $name
     * @return Column|null
    */
    public function getColumn(string $name): ?Column
    {
         return $this->columns->getColumn($name);
    }




    /**
     * @inheritDoc
    */
    public function getColumns()
    {
         return $this->columns->getColumns();
    }




    /**
     * @return string
    */
    public function getTable(): string
    {
         return $this->table;
    }




    /**
     * @param $name
     * @param $type
     * @param $length
     * @return $this
    */
    public function addColumn($name, $type, $length = null): self
    {
         $this->alteredColumns[] = "ALTER TABLE {$this->table} ADD {$name} {$type($length)}";

         return $this;
    }




    /**
     * @param $name
     * @return $this
    */
    public function dropColumn($name): self
    {
        $this->alteredColumns[] =  sprintf('ALTER TABLE %s DROP COLUMN %s', $this->table, $name);

         return $this;
    }






    /**
     * @param $name
     * @param $type
     * @param int $length
     * @return $this
    */
    public function modifyColumn($name, $type, $length = 0): self
    {
         $type = $length ? $type($length) : $type;

         $this->alteredColumns[] = sprintf('ALTER TABLE %s MODIFY %s %s', $this->table, $name, $type);

         return $this;
    }




    /**
     * @return array
    */
    public function getAlteredColumns(): array
    {
        return $this->alteredColumns;
    }


}