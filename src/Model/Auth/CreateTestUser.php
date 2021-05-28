<?php 

namespace App\Model\Auth;

use App\Model\Auth\User;
use App\Model\Database\Repository\UserRepository;

class CreateTestUser
{
    public static function getTestUser(string $roles): User
    {
        $user = (new UserRepository)->findAll()[0];
        
        $user['roles'] = $roles;
        
        $testUser = new User($user);

        return $testUser;
    }
}