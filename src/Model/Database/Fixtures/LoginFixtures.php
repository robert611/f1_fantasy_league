<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class LoginFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [];
    }

    public function load(): void
    {
        $loginRecords = $this->getRecords();

        foreach ($loginRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO login VALUES (null, :user_id, :date)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM login");
    }
}