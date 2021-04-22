<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;
use App\Model\Database\QueryBuilder;

class UserFixtures extends Fixture implements FixturesInterface
{
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function getUserRecords(): array
    {
        return [
            ['username' => 'tomy1242', 'email' => 'test1242@email.com', 'roles' => '["ROLE_USER"]', 'password' => 'tomy1242'],
            ['username' => 'maria1942', 'email' => 'maria1942@email.com', 'roles' => '["ROLE_USER"]', 'password' => 'maria1942'],
            ['username' => 'michael11', 'email' => 'michael11@email.com', 'roles' => '["ROLE_USER"]', 'password' => 'michael11'],
            ['username' => 'veronica935', 'email' => 'veronica935@email.com', 'roles' => '["ROLE_USER"]', 'password' => 'veronica935'],
            ['username' => 'john9022', 'email' => 'john902@email.com', 'roles' => '["ROLE_USER"]', 'password' => 'john902']
        ];
    }

    public function load(): void
    {
        $pdo = $this->getPdoConnection();

        $userRecords = $this->getUserRecords();

        foreach ($userRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO user VALUES (null, :username, :email, :roles, :password)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM user");
    }
}