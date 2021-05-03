<?php 

namespace App\Test\Database;

use PHPUnit\Framework\TestCase;
use App\Model\Database\Repository\UserRepository;
use App\Model\Auth\Authentication;
use App\Model\Database\Fixtures\UserFixtures;

final class AuthenticationTest extends TestCase
{
    private UserRepository $userRepository;
    private Authentication $authentication;
    private UserFixtures $userFixtures;

    public function setUp(): void
    {
        $this->userRepository = new UserRepository();
        $this->authentication = new Authentication();
        $this->userFixtures = new UserFixtures();

        $this->userFixtures->clear();
        $this->userFixtures->load();
    }

    public function test_if_user_can_be_find_in_database()
    {
        $user = $this->userRepository->findAll()[0];
        
        $foundUser = $this->authentication->getUser($user['username']);

        $this->assertTrue(is_array($foundUser));
        $this->assertEquals($foundUser['username'], $user['username']);
        $this->assertEquals($foundUser['email'], $user['email']);
        $this->assertEquals($foundUser['roles'], $user['roles']);
    }

    public function test_if_not_existing_user_will_not_be_find_in_database()
    {        
        $foundUser = $this->authentication->getUser('not_existing_user_test_p');

        $this->assertFalse($foundUser);
    }

    public function test_if_passwords_can_be_correctly_compared()
    {
        $user = $this->userRepository->findAll()[0];

        $rawPassword = $this->userFixtures->getUserRecords()[0]['raw_password'];

        $isPasswordCorrect = $this->authentication->isPasswordCorrect($user, $rawPassword);
        $wrongPassword = $this->authentication->isPasswordCorrect($user, 'wrong_password_test_xxx');

        $this->assertTrue($isPasswordCorrect);
        $this->assertFalse($wrongPassword);
    }
}