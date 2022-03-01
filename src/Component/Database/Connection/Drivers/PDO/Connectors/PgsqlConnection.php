<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;


use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;
use Laventure\Component\Database\Connection\Exception\StatementException;
use LogicException;


/**
 * @PostgresConnection
*/
class PgsqlConnection extends PdoConnection
{


    /**
     * @return string
    */
    public function getName(): string
    {
        return 'pgsql';
    }



    /**
     * @return int
     * @throws StatementException|LogicException
    */
    public function lastIdValue(): int
    {
        return (int) $this->query('SELECT last_value FROM id_seq;')
                         ->getOneOrNullResult();
    }




    /**
     * @inheritDoc
     * @throws LogicException
    */
    public function createDatabase()
    {
        $sqlGeneric = sprintf(
           "SELECT 'CREATE DATABASE %s' 
                  WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '%s')",
            $this->config['database'],
            $this->config['database']
        );

        $this->exec($sqlGeneric);
    }




    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function dropDatabase()
    {
        $sqlGeneric =  sprintf(
            "SELECT pg_terminate_backend(pid) 
                   FROM pg_stat_activity 
                   WHERE pid <> pg_backend_pid() AND datname = '%s';",
            $this->config['database']
        );

        $this->exec($sqlGeneric);
    }


    /**
     * @inheritDoc
     * @throws LogicException
     */
    public function createTable($table, string $columns, array $alterColumns = [])
    {
        $columns = ltrim($columns, '(');
        $columns = rtrim($columns, ')');

        $table = $this->getTableRealName($table);


        $sqlGeneric = sprintf("CREATE TABLE IF NOT EXISTS %s (%s);", $table, $columns);

        $this->exec($sqlGeneric);

        if ($alterColumns) {
            // todo something alter table
        }
    }


    /**
     * @inheritDoc
     * @throws LogicException
    */
    public function dropTable($table)
    {
        $sqlGeneric = sprintf('DROP TABLE %s;', $this->getTableRealName($table));

        $this->exec($sqlGeneric);
    }



    /**
     * @inheritDoc
     * @throws LogicException
    */
    public function dropIfExistsTable($table)
    {
        $sqlGeneric = sprintf('DROP TABLE IF EXISTS %s;', $this->getTableRealName($table));

        $this->exec($sqlGeneric);
    }


    /**
     * @inheritDoc
     * @throws LogicException
    */
    public function truncateTable($table)
    {
        $sqlGeneric = sprintf('TRUNCATE TABLE %s;', $this->getTableRealName($table));

        $this->exec($sqlGeneric);
    }


    /**
     * @inheritDoc
     * @throws LogicException
     * @throws StatementException
    */
    public function showTables()
    {
        $tables = [];

        foreach ($this->showInformationSchema() as $information) {
            $tables[] = $information->tablename;
        }

        return $tables;
    }



    /**
     * @return array|false|mixed
     * @throws LogicException
     * @throws StatementException
     */
    protected function showInformationSchema()
    {
        $sqlGeneric = "SELECT * FROM pg_catalog.pg_tables 
                WHERE schemaname != 'pg_catalog' 
                AND schemaname != 'information_schema';
                ";

        return $this->query($sqlGeneric)->getResult();
    }


    /**
     * @inheritDoc
     * @throws LogicException|StatementException
     */
    public function describeTable($table)
    {
        $table = $this->getTableRealName($table);

        $this->exec($this->describeTableSQLFunction());

        $sqlGeneric = sprintf("select  *  from describe_table('%s')", $table);

        return $this->query($sqlGeneric)
                    ->withFetchMode(\PDO::FETCH_ASSOC)
                    ->getResult();
    }



    /**
     * @return string
     */
    protected function describeTableSQLFunction(): string
    {
        return "create or replace function describe_table(tbl_name text) 
                returns table(column_name varchar, data_type varchar,character_maximum_length int) as $$
                select column_name, data_type, character_maximum_length
                from INFORMATION_SCHEMA.COLUMNS where table_name = $1;
                $$
                language 'sql';
            ";
    }


    /**
     * @param $table
     * @return void
     * @throws StatementException
    */
    public function showTableColumns($table)
    {
         $schemaParams = $this->transformSchemaInformationToArray($table);

         return $schemaParams['column_name'];
    }




    /**
     * @throws StatementException
    */
    protected function transformSchemaInformationToArray($table): array
    {
        $schemaInfos = $this->describeTable($table);

        $informationParams = [];

        foreach ($schemaInfos as $params) {
            $params = (array) $params;
            foreach ($params as $key => $value) {
                $informationParams[$key][] = $value;
            }
        }

        return $informationParams;
    }

}