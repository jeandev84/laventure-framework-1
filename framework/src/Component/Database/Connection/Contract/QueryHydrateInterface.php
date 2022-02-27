<?php
namespace Laventure\Component\Database\Connection\Contract;


/**
 * @QueryHydrateInterface
*/
interface QueryHydrateInterface
{

     /**
      * get all items
      *
      * @return mixed
     */
     public function getResult();


     /**
      * get one or null item
      *
      * @return mixed
     */
     public function getOneOrNullResult();


     /**
      * get array columns
      *
      * @return mixed
     */
     public function getArrayColumns();



     /**
      * get first result
      *
      * @return mixed
     */
     public function getFirstResult();
}