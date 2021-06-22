<?php 

namespace App\Model\Database\Repository;

use App\Model\Database\Connection;
use App\Model\Database\QueryBuilder;

abstract class AbstractRepository
{
    use Connection;
    
    private \PDO $pdo;
    protected QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->pdo = $this->getPdoConnection();
        $this->queryBuilder = new QueryBuilder();
    }

    /*
    * Contains name of the table associated with repository
    */
    public string $table;

    /*
    * Fetches row from table with given id
    */
    public function find(int $id)
    {
        try {
            $dbh = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
            $dbh->execute(['id' => $id]);
        }
        catch (\PDOException $e)
        {
            echo $e->getMessage();
        }

        return $dbh->fetch(\PDO::FETCH_ASSOC);
    }

    /*
    * Fetches all rows from table
    */
    public function findAll(): ?array
    {
        try {
            $dbh = $this->pdo->prepare("SELECT * FROM " . $this->table);
            $dbh->execute();
        }
        catch (\PDOException $e)
        {
            echo $e->getMessage();
        }

        return $dbh->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByQuery(array $conditions): object
    {
        $query = "SELECT * FROM " . $this->table . "";

        if (!empty($conditions))
        {
            $query .= " WHERE ";
        } 

        $iteration = 1;

        foreach ($conditions as $column => $condition)
        {
            if ($iteration > 1)
            {
                $query .= " AND " . $column . " = :" . $column;
            }
            else 
            {
                $query .= $column . " = :" . $column;
            }

            $iteration++;
        }

        try {
            $dbh = $this->pdo->prepare($query);
            $dbh->execute($conditions);
        }  
        catch (\PDOException $e)
        {
            echo $e->getMessage();
        }

        return $dbh;
    }

    /*
    * Fetch a record which matches given conditions
    */
    public function findOneBy(array $conditions)
    {
        $dbh = $this->findByQuery($conditions);

        return $dbh->fetch(\PDO::FETCH_ASSOC);
    }

    /*
    * Fetch all records which matches given conditions
    */
    public function findBy(array $conditions): ?array
    {
        $dbh = $this->findByQuery($conditions);

        return $dbh->fetchAll(\PDO::FETCH_ASSOC);
    }

    /*
    * Fetch table's first record
    */
    public function first(): false | array
    {
        return $this->queryBuilder->queryWithFetch("SELECT * FROM {$this->table} LIMIT 1");
    }
}