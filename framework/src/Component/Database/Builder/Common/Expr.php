<?php
namespace Laventure\Component\Database\Builder\Common;


/**
 * @Expr
*/
class Expr
{

     protected $qb;


     public function __construct(SqlBuilder $qb)
     {
          $this->qb = $qb;
     }


     public function count()
     {
         return "";
     }



     public function sum()
     {
          return  "";
     }


     public function avg()
     {
         return "";
     }
}