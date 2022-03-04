<?php
namespace Laventure\Component\Database\Connection\Contract;


/**
 * @ConnectionInterface
*/
interface ConnectionInterface
{
    public function getName();
    public function connect($config);
    public function connected(): bool;
    public function getConnection();
    public function createQuery();
    public function beginTransaction();
    public function commit();
    public function rollback();
    public function lastInsertId();
    public function exec($sql);
    public function disconnect();


    public function createDatabase();
    public function dropDatabase();
    public function createTable($table, string $printColumns);
    public function dropTable($table);
    public function dropIfExistsTable($table);
    public function truncateTable($table);
    public function showTables();
    public function showTableColumns($table);
    public function describeTable($table);
}