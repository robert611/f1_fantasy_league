<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;
use App\Model\Database\QueryBuilder;

class LoginFixtures extends Fixture implements FixturesInterface
{
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function getLoginRecords(): array
    {
        return [];
    }

    public function load(): void
    {
        $loginRecords = $this->getLoginRecords();

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