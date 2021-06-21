<?php

namespace App\Model\Database\Repository;

class UserRepository extends AbstractRepository
{
    public string $table = 'user';

    public function saveUser(string $username, string $email, string $password, string $roles): void
    {
        $query = "INSERT INTO user VALUES (null, :username, :email, :roles, :password)";

        $this->queryBuilder->executeQuery($query, ['username' => $username, 'email' => $email, 'password' => $password, 'roles' => $roles]);
    }

    public function findUsersIds(): array
    {
        return $this->queryBuilder->queryWithFetchAll(query: "SELECT id FROM user", format: \PDO::FETCH_COLUMN);
    }
}