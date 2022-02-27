<?php
namespace Laventure\Component\Database\Migration;


use Laventure\Component\Database\Migration\Common\AbstractMigration;



/**
 * @Migration
*/
class Migration extends AbstractMigration
{

     /**
      * @inheritDoc
     */
     public function up() {}



     /**
      * @inheritDoc
     */
     public function down() {}




    /**
     * @return string
    */
    public function getName(): string
    {
        return (new \ReflectionObject($this))->getShortName();
    }



    /**
     * @return false|string
    */
    public function getPath()
    {
        return (new \ReflectionObject($this))->getFileName();
    }
}