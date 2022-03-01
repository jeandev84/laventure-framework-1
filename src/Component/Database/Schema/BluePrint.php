<?php
namespace Laventure\Component\Database\Schema;


use Exception;
use Laventure\Component\Database\Schema\Column\BluePrintColumn;
use Laventure\Component\Database\Schema\Column\Column;
use Laventure\Component\Database\Schema\Column\Contract\BluePrintColumnInterface;


/**
 * @BluePrint
*/
class BluePrint
{

      /**
       * @var BluePrintColumn
      */
      protected $bluePrint;





      /**
       * @param BluePrintColumn $bluePrint
      */
      public function __construct(BluePrintColumn $bluePrint)
      {
             $this->bluePrint = $bluePrint;
      }




      /**
       * @return mixed
      */
      public function getTable()
      {
          return $this->bluePrint->getTable();
      }



      /**
       * @param $name
       * @return Column
      */
      public function increments($name): Column
      {
          return $this->bluePrint->increments($name);
      }




      /**
       * @param $name
       * @param int $length
       * @return Column
      */
      public function integer($name, int $length = 11): Column
      {
           return $this->bluePrint->integer($name, $length);
      }



      /**
       * @param string $name
       * @param int $length
       * @return Column
      */
      public function string(string $name, int $length = 255): Column
      {
            return $this->bluePrint->string($name, $length);
      }



      /**
       * @param $name
       * @return Column
      */
      public function boolean($name): Column
      {
          return $this->bluePrint->boolean($name);
      }



      /**
       * @param $name
       * @return Column
      */
      public function text($name): Column
      {
         return $this->bluePrint->text($name);
      }




     /**
      * @param $name
      * @return Column
     */
     public function datetime($name): Column
     {
         return $this->bluePrint->datetime($name);
     }





    /**
     * @return void
    */
    public function timestamps()
    {
        $this->bluePrint->timestamps();
    }




    /**
     * @param bool $status
     * @return void
     * @throws Exception
    */
    public function softDeletes(bool $status = false)
    {
         $this->bluePrint->softDeletes($status);
    }





    /**
     * @return string
    */
    public function printColumns(): string
    {
         return $this->bluePrint->printColumns();
    }





    /**
     * @param $name
     * @param $type
     * @param $length
     * @return BluePrintColumn
    */
    public function add($name, $type, $length = null): BluePrintColumn
    {
        return $this->bluePrint->addColumn($name, $type, $length);
    }




    /**
     * @param $name
     * @param $type
     * @param $length
     * @return BluePrintColumn
    */
    public function modify($name, $type, $length = 0): BluePrintColumn
    {
        return $this->bluePrint->modifyColumn($name, $type, $length);
    }




    /**
     * @param $name
     * @return BluePrintColumn
    */
    public function drop($name): BluePrintColumn
    {
        return $this->bluePrint->dropColumn($name);
    }





    /**
     * @return array
    */
    public function getAlteredColumns(): array
    {
        return $this->bluePrint->getAlteredColumns();
    }
}