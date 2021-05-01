<?php 

namespace App\Test\Database;

use App\Model\Auth\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        $this->user = new User(['username' => 'test_user', 'email' => 'test@email.com', 'roles' => '["ROLE_USER", "ROLE_ADMIN"]']);
    }

    public function test_if_user_roles_can_be_fetched()
    {
        $roles = $this->user->getRoles();

        $this->assertEquals($roles, ["ROLE_USER", "ROLE_ADMIN"]);
    }

    public function test_if_it_can_be_checked_if_user_have_some_role()
    {
        $hasRole = $this->user->hasRole("ROLE_USER");
        $doesNotHaveRole = $this->user->hasRole("ROLE_TEST");

        $this->assertTrue($hasRole);
        $this->assertFalse($doesNotHaveRole);
    }

    public function test_if_username_can_be_fetched()
    {
        $this->assertEquals($this->user->getUsername(), 'test_user');
    }

    public function test_if_email_can_be_fetched()
    {
        $this->assertEquals($this->user->getEmail(), 'test@email.com');
    }
}