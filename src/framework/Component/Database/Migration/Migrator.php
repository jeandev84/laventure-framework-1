<?php
namespace Laventure\Component\Database\Migration;


use Laventure\Component\Database\Migration\Common\AbstractMigrator;


/**
 * @Migrator
*/
class Migrator extends AbstractMigrator
{


    /**
     * Create a migration table
     *
     * @return void
    */
    public function install()
    {
        (function () {
            $this->createMigrationTable();
        })();
    }



    /**
     * Diff migration
     *
     * @return void
    */
    public function diff() {}

}