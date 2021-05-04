<?php 

namespace App\Model\Auth;

use App\Model\Database\Repository\UserRepository;

class Registration 
{
    private array $validationErrors;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->validationErrors = [];
        $this->userRepository = new UserRepository();
    }

    public function validateUserData(string $username, string $email, string $password, string $repeatedPassword): bool
    {
        if ($this->userRepository->findOneBy(['username' => $username]) !== false)
        {
            $this->addValidationError("Given username is already taken");
        }

        if (strlen($username) < 6 || strlen($username) > 32)
        {
            $this->addValidationError("Username must be beetwen 6 and 32 characters");
        }

        if ($this->userRepository->findOneBy(['email' => $email]) !== false)
        {
            $this->addValidationError("Given email is already taken");
        }

        if ($password !== $repeatedPassword)
        {
            $this->addValidationError("Your passwords do not match");
        }

        if (strlen($password) < 8 || strlen($password) > 64)
        {
            $this->addValidationError("Password must be beetwen 8 and 64 characters");
        }

        if ($username === $password)
        {
            $this->addValidationError("Username and password must be diffrent");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->addValidationError("Given email is incorrect");
        }

        return empty($this->getValidationErrors());
    }

    public function addValidationError(string $validationError): void
    {
        $this->validationErrors[] = $validationError;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}