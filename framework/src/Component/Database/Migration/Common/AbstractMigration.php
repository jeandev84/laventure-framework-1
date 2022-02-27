<?php
namespace Laventure\Component\Database\Migration\Common;



use Laventure\Component\Database\Migration\Contract\MigrationInterface;



/**
 * @AbstractMigration
*/
abstract class AbstractMigration implements MigrationInterface
{

      /**
       * @return string
      */
      abstract public function getName(): string;




      /**
       * @return false|string
      */
      abstract public function getPath();
}