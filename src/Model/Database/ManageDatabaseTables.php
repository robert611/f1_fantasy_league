<?php 

namespace App\Model\Database;

use App\Model\Database\QueryBuilder;

class ManageDatabaseTables
{
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function getQueriesCreatingTables(): array
    {
        return [
            'user' => 'CREATE TABLE user (id INT PRIMARY KEY AUTO_INCREMENT, username VARCHAR(180) unique NOT NULL, email VARCHAR(180) unique NOT NULL, roles longtext DEFAULT NULL, password VARCHAR(2048) NOT NULL)',
            'team' => 'CREATE TABLE team (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(128), picture VARCHAR(128))',
            'driver' => 'CREATE TABLE driver (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(128), driver_number smallint, team_id int)',
            'race' => 'CREATE TABLE race (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(128), race_start text)',
            'login' => 'CREATE TABLE login (id INT PRIMARY KEY AUTO_INCREMENT, user_id INT, date VARCHAR(32))'
        ];
    }

    public function getQueriesCreatingForeignKeys()
    {
        return [
            'ALTER TABLE driver ADD FOREIGN KEY (team_id) REFERENCES team(id)',
            'ALTER TABLE login ADD FOREIGN KEY (user_id) REFERENCES user(id)'
        ];
    }

    public function getTablesNames(): array
    {
        $tablesNames = [];

        $queriesCreatingTables = $this->getQueriesCreatingTables();

        foreach ($queriesCreatingTables as $tableName => $query)
        {
            $tablesNames[] = $tableName;
        }

        return $tablesNames;
    }

    public function createTables()
    {
        $queriesCreatingTables = $this->getQueriesCreatingTables();

        foreach ($queriesCreatingTables as $query)
        {
            $this->queryBuilder->executeQuery($query);
        }
    }

    public function createForeignKeys(): void
    {
        $queriesCreatingForeignKeys = $this->getQueriesCreatingForeignKeys();

        foreach ($queriesCreatingForeignKeys as $query)
        {
            $this->queryBuilder->executeQuery($query);
        }
    }

    public function dropTables(array $tablesToDrop)
    {
        foreach ($tablesToDrop as $tableName)
        {
            $this->queryBuilder->executeQuery("DROP TABLE IF EXISTS $tableName");
        }
    }
}