<?php 

namespace App\Model\Database;

use App\Model\Database\Connection;

class QueryBuilder
{
    use connection;

    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = $this->getPdoConnection();
    }

    private function basicQuery(string $query, array $values = [])
    {
        try {
            $dbh = $this->pdo->prepare($query);
            $dbh->execute($values);
        }
        catch (\PDOException $e)
        {
            return $e->getMessage();
        }

        return $dbh;
    }

    public function executeQuery(string $query, array $values = []): ?string
    {
        $this->basicQuery($query, $values);

        return null;
    }

    public function queryWithFetch(string $query, array $values = [])
    {
        $dbh = $this->basicQuery($query, $values);

        return $dbh->fetch(\PDO::FETCH_ASSOC);
    }

    public function queryWithFetchAll(string $query, array $values = [])
    {
        $dbh = $this->basicQuery($query, $values);
    
        return $dbh->fetchAll(\PDO::FETCH_ASSOC);
    }
}