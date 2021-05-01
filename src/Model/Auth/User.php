<?php 

namespace App\Model\Auth;

class User 
{
    private string $username;
    private string $email;
    private array $roles;

    public function __construct(array $userData)
    {
        $this->username = $userData['username'];
        $this->email = $userData['email'];

        $this->roles = $this->decodeRoles($userData['roles']);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    private function decodeRoles(string $roles): array
    {
        return json_decode($roles);
    }
}