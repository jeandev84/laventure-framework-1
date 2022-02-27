<?php
namespace Laventure\Component\Database\Migration\Contract;


/**
 * @MigrationInterface
*/
interface MigrationInterface
{

     /**
      * Create schema table and do some changes
      *
      * @return mixed
     */
     public function up();



     /**
      * Drop table and do some changes
      *
      * @return mixed
     */
     public function down();




     /**
      * Get migration name
      *
      * @return mixed
     */
     public function getName();




     /**
      * @return mixed
     */
     public function getPath();
}