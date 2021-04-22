<?php

namespace App\Model\Database;

use App\Config\Database as DatabaseConfig;

trait Connection 
{
    public function getPdoConnection(): \PDO
    {
        $config = DatabaseConfig::getDatabaseConfiguration();

        $user = $config['user'];
        $password = $config['password'];
        $databaseName = $config['database_name'];

        try {
            $pdo = new \PDO("mysql:dbname=$databaseName;host=localhost", $user, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo $e->getMessage();

            die();
        }
        
        return $pdo;
    }
}