<?php 

namespace App\Model\Auth;

use App\Model\Database\Repository\UserRepository;

class Authentication
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getUser(string $username)
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        return $user;
    }

    public function isPasswordCorrect(array $user, string $password): bool
    {
        return password_verify($password, $user['password']);
    }
}