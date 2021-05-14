<?php

namespace App\Model\Database\Repository;

class LoginRepository extends AbstractRepository
{
    public string $table = 'login';

    public function saveLogin(string $userId): void
    {
        $currentDate = date('Y-m-d H:i:s');

        $query = "INSERT INTO login VALUES (null, :user_id, :date)";

        $this->queryBuilder->executeQuery($query, ['user_id' => $userId, 'date' => $currentDate]);
    }
}