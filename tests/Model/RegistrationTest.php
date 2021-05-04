<?php 

namespace App\Test\Database;

use App\Model\Auth\Registration;
use PHPUnit\Framework\TestCase;
use App\Model\Database\QueryBuilder;

final class RegistrationTest extends TestCase
{
    private Registration $registrationModel;
    private QueryBuilder $queryBuilder;
    private array $userData;

    public function setUp(): void
    {
        $this->registrationModel = new Registration();
        $this->queryBuilder = new QueryBuilder();

        $this->userData['username'] = 'username';
        $this->userData['email'] = 'testemail123@interia.pl';
        $this->userData['password'] = 'password1234';
        $this->userData['repeated_password']  = 'password1234';
    }

    public function testIfTakenUsernameWillBeDetected()
    {
        $username = $this->queryBuilder->queryWithFetch("SELECT * FROM user LIMIT 1")['username'];

        $isValid = $this->registrationModel->validateUserData($username, $this->userData['email'], $this->userData['password'], $this->userData['repeated_password']);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 1);
    }

    public function testTooShortUsernameLength()
    {
        $username = 'short';

        $isValid = $this->registrationModel->validateUserData($username, $this->userData['email'], $this->userData['password'], $this->userData['repeated_password']);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 1);
    }

    public function testTooLongUsernameLength()
    {
        $username = 'extremely_long_username_over_32_characters';

        $isValid = $this->registrationModel->validateUserData($username, $this->userData['email'], $this->userData['password'], $this->userData['repeated_password']);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 1);
    }

    public function testIfTakenEmailWillBeDetected()
    {
        $email = $this->queryBuilder->queryWithFetch("SELECT * FROM user LIMIT 1")['email'];

        $isValid = $this->registrationModel->validateUserData($this->userData['username'], $email, $this->userData['password'], $this->userData['repeated_password']);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 1);
    }

    public function testIfPasswordsMustBeTheSame()
    {
        $repeatedPassword = 'it_is_not_correctly_repeated_password';

        $isValid = $this->registrationModel->validateUserData($this->userData['username'], $this->userData['email'], $this->userData['password'], $repeatedPassword);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 1);
    }

    public function testToShortPasswordLength()
    {
        $password = 'short';

        $isValid = $this->registrationModel->validateUserData($this->userData['username'], $this->userData['email'], $password, $this->userData['repeated_password']);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 2);
    }

    public function testToLongPasswordLength()
    {
        $password = 'extremely_long_password_over_64_charachters_repeat_charachters_repeat_characters_repeat_characters';

        $isValid = $this->registrationModel->validateUserData($this->userData['username'], $this->userData['email'], $password, $this->userData['repeated_password']);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 2);
    }

    public function testIfUsernameAndPasswordAreNotTheSame()
    {
        $username = "the_same_as_password";
        $password = "the_same_as_password";

        $isValid = $this->registrationModel->validateUserData($username, $this->userData['email'], $password, $this->userData['repeated_password']);

        $errorsCount = count($this->registrationModel->getValidationErrors());

        $this->assertFalse($isValid);
        $this->assertEquals($errorsCount, 2);
    }
}