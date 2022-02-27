<?php
namespace Laventure\Component\Database\Schema;


use Exception;
use Laventure\Component\Database\Schema\Column\Column;
use Laventure\Component\Database\Schema\Column\Contract\BluePrintColumnInterface;


/**
 * @BluePrint
*/
class BluePrint
{

      /**
       * @var BluePrintColumnInterface
      */
      protected $bluePrint;




      /**
       * @param BluePrintColumnInterface $bluePrint
      */
      public function __construct(BluePrintColumnInterface $bluePrint)
      {
             $this->bluePrint = $bluePrint;
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
}