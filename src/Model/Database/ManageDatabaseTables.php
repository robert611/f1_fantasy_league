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
            'user' => 'CREATE TABLE user (id INT PRIMARY KEY AUTO_INCREMENT, username VARCHAR(180) unique NOT NULL, email VARCHAR(180) unique NOT NULL, roles longtext DEFAULT NULL, password VARCHAR(2048) NOT NULL)'
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

    public function dropTables(array $tablesToDrop)
    {
        foreach ($tablesToDrop as $tableName)
        {
            $this->queryBuilder->executeQuery("DROP TABLE IF EXISTS $tableName");
        }
    }
}