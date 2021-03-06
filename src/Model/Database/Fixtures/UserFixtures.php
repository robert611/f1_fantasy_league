<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class UserFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [
            ['username' => 'tomy1242', 'email' => 'test1242@email.com', 'roles' => '["ROLE_USER", "ROLE_ADMIN"]', 'raw_password' => 'tomy1242', 'password' => password_hash('tomy1242', PASSWORD_BCRYPT)],
            ['username' => 'maria1942', 'email' => 'maria1942@email.com', 'roles' => '["ROLE_USER"]', 'raw_password' => 'maria1942', 'password' => password_hash('maria1942', PASSWORD_BCRYPT)],
            ['username' => 'michael11', 'email' => 'michael11@email.com', 'roles' => '["ROLE_USER"]', 'raw_password' => 'michael11','password' => password_hash('michael11', PASSWORD_BCRYPT)],
            ['username' => 'veronica935', 'email' => 'veronica935@email.com', 'roles' => '["ROLE_USER"]', 'raw_password' => 'veronica935','password' => password_hash('veronica935', PASSWORD_BCRYPT)],
            ['username' => 'john9022', 'email' => 'john902@email.com', 'roles' => '["ROLE_USER"]', 'raw_password' => 'john902', 'password' => password_hash('john902', PASSWORD_BCRYPT)]
        ];
    }

    public function load(): void
    {
        $userRecords = $this->getRecords();

        foreach ($userRecords as $record)
        {
            unset($record['raw_password']);
            $this->queryBuilder->executeQuery("INSERT INTO user VALUES (null, :username, :email, :roles, :password)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM user");
    }
}