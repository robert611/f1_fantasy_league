<?php 

namespace App\Test\Database\Repository;

use App\Model\Database\QueryBuilder;
use App\Model\Database\Repository\UserRepository;
use App\Model\Database\Fixtures\UserFixtures;
use PHPUnit\Framework\TestCase;

final class UserRepositoryTest extends TestCase
{
    private $queryBuilder;
    private $userRepository;
    private $userFixtures;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->userRepository = new UserRepository();
        $this->userFixtures = new UserFixtures();

        $this->userFixtures->clear();
        $this->userFixtures->load();
    }

    public function testFind()
    {
        $userId = $this->queryBuilder->queryWithFetch("SELECT * FROM user LIMIT 1")['id'];

        $user = $this->userRepository->find($userId);

        $expectedUser = $this->userFixtures->getUserRecords()[0];

        $this->assertEquals($user['username'], $expectedUser['username']);
        $this->assertEquals($user['email'], $expectedUser['email']);
        $this->assertEquals($user['password'], $expectedUser['password']);
        $this->assertEquals($user['roles'], $expectedUser['roles']);
    }

    public function testFindAll()
    {
        $expectedUserCount = count($this->queryBuilder->queryWithFetchAll("SELECT * FROM user"));

        $usersCount = count($this->userRepository->findAll());

        $this->assertEquals($expectedUserCount, $usersCount);
    }

    public function testFindOneBy()
    {
        $username = $this->userFixtures->getUserRecords()[0]['username'];

        $expectedUser = $this->queryBuilder->queryWithFetch("SELECT * FROM user WHERE username = :username", ['username' => $username]);

        $user = $this->userRepository->findOneBy(['username' => $username]);

        $this->assertEquals($user['username'], $expectedUser['username']);
        $this->assertEquals($user['email'], $expectedUser['email']);
        $this->assertEquals($user['password'], $expectedUser['password']);
        $this->assertEquals($user['roles'], $expectedUser['roles']);
    }

    public function testFindBy()
    {
        $expectedUsers = $this->queryBuilder->queryWithFetchAll("SELECT * FROM user WHERE roles = :roles", ['roles' => '["ROLE_USER"]']);

        $users = $this->userRepository->findOneBy(['roles' => '["ROLE_USER"]']);

        $this->assertEquals(count($users), count($expectedUsers));
    }
}