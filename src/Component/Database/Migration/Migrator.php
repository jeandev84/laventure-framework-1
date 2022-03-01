<?php
namespace Laventure\Component\Database\Migration;


use Exception;
use Laventure\Component\Database\Migration\Common\AbstractMigrator;
use Laventure\Component\Database\Migration\Contract\MigrationInterface;
use Laventure\Component\Database\Migration\Contract\MigratorInterface;
use Laventure\Component\Database\Migration\Exception\MigrationException;
use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\Database\Schema\BluePrint;
use Laventure\Component\Database\Schema\Schema;


/**
 * @Migrator
*/
class Migrator implements MigratorInterface
{


    /**
     * @var string
     */
    protected $tableName = 'laventure_migrations';



    /**
     * @var MigrationCollection
     */
    protected $migrations;




    /**
     * @var EntityManager
     */
    protected $em;




    /**
     * @var Schema
     */
    protected $schema;





    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
         $this->em         = $em;
         $this->schema     = new Schema($em->getConnectionManager());
         $this->migrations = new MigrationCollection();
         $this->em->withTable($this->getTableName());
    }




    /**
     * @param MigrationInterface $migration
     * @return $this
     */
    public function addMigration(MigrationInterface $migration): self
    {
        $this->migrations->addMigration($migration);

        return $this;
    }




    /**
     * @param array $migrations
     * @return void
     */
    public function addMigrations(array $migrations)
    {
        $this->migrations->addMigrations($migrations);
    }



    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }



    /**
     * @param string $tableName
     * @return $this
     */
    public function withTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }




    /**
     * @inheritDoc
    */
    public function getMigrations()
    {
        return $this->migrations->getMigrations();
    }




    /**
     * @inheritDoc
    */
    public function createMigrationTable()
    {
        $this->schema->create($this->tableName, function (BluePrint $table) {
            $table->increments('id');
            $table->string('version');
            $table->datetime('executed_at');
            $table->boolean('executed');
        });
    }






    /**
     * @inheritDoc
     * @throws \Exception
    */
    public function migrate()
    {
        try {

            $this->em->transaction(function () {

                $this->createMigrationTable();

                $oldMigrations = $this->getOldMigrations();

                $newMigrations  = $this->migrations->getNewMigrations(
                    $oldMigrations
                );

                $this->up($newMigrations);
            });

            return true;

        } catch (Exception $e) {

            throw new MigrationException($e->getMessage(), $e->getCode());
        }
    }




    /**
     * Diff migration
     *
     * @return void
    */
    public function diff() {}





    /**
     * @param array $migrations
     * @return void
     * @throws Exception
    */
    protected function up(array $migrations)
    {
        $this->saveMigrations($migrations);
    }



    /**
     * Drop all created tables.
    */
    protected function down(array $migrations)
    {
        foreach ($migrations as $migration) {
            if (method_exists($migration, 'down')) {
                $migration->down();
            }
        }
    }




    /**
     * @param array $migrations
     * @return void
     * @throws Exception
     */
    protected function saveMigrations(array $migrations)
    {
        /** @var MigrationInterface $migration */
        foreach ($migrations as $migration) {
            $this->saveMigration($migration);
        }
    }





    /**
     * @param MigrationInterface $migration
     * @throws Exception
     */
    protected function saveMigration(MigrationInterface $migration)
    {
        if (method_exists($migration, 'up')) {
            $migration->up();
        }

        $qb = $this->em->createQueryBuilder();

        $qb->insert($this->getAttributes($migration));
    }




    /**
     * @param MigrationInterface $migration
     * @return array
     */
    protected function getAttributes(MigrationInterface $migration): array
    {
        return [
            'version'     => $migration->getName(),
            'executed_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'executed'    => 1
        ];
    }



    /**
     * @inheritDoc
     */
    public function rollback()
    {
        $this->em->transaction(function () {

            $this->down($this->getMigrations());

            $this->schema->truncate($this->tableName);
        });
    }




    /**
     * Reset migrations, it's revert all migrations
     *
     * @throws Exception
     */
    public function reset()
    {
        $this->rollback();
        $this->schema->dropIfExists($this->tableName);
        $this->migrations->removeMigrationFiles();
    }



    /**
     * @return array
     * @throws \Exception
     */
    private function getOldMigrations(): array
    {
        $columnReference = trim($this->getReferenceColumn(), '`');

        return $this->em->createQueryBuilder()
            ->select([$columnReference])
            ->from($this->tableName)
            ->getQuery()
            ->getArrayColumns();
    }



    /**
     * @return string
    */
    protected function getReferenceColumn(): string
    {
        return 'version';
    }

}