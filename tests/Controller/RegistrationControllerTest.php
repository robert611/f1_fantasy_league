<?php 

namespace App\Tests\Controller;

use App\Model\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RegistrationControllerTest extends TestCase
{
    private QueryBuilder $queryBuilder;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function test_if_registration_form_can_be_used()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000', 'verify' => false]);

        $response = $client->get('/register');

        $responseBody = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(str_contains($responseBody, 'Register'));
    }

    public function test_if_user_can_register()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000', 'verify' => false]);

        $response = $client->request('POST', '/register', [
            'form_params' => [
                'username' => 'test_username_xx',
                'email' => 'test_email@example.com',
                'password' => 'test_password',
                'password-repeat' => 'test_password'
            ]
        ]);

        $user = $this->queryBuilder->queryWithFetch("SELECT * FROM user where username = :username", ['username' => 'test_username_xx']);

        $this->assertEquals($user['username'], 'test_username_xx');
        $this->assertEquals($user['email'], 'test_email@example.com');
        $this->assertTrue(password_verify('test_password', $user['password']));

        $user = $this->queryBuilder->executeQuery("DELETE FROM user WHERE username = :username", ['username' => 'test_username_xx']);
    }

    public function test_if_user_will_not_be_registered_in_case_of_failed_registration()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000', 'verify' => false, 'allow_redirects' => ['track_redirects' => true]]);

        $response = $client->request('POST', '/register', [
            'form_params' => [
                'username' => 'tes',
                'email' => 'test_email_test@example.com',
                'password' => 'test_password',
                'password-repeat' => 'test_password'
            ]
        ]);

        $user = $this->queryBuilder->queryWithFetch("SELECT * FROM user where username = :username", ['username' => 'tes']);
        
        $this->assertFalse($user);
    }

    public function test_if_user_will_be_redirected_to_register_page_after_failed_registration()
    {
        $client = new Client(['base_uri' => 'http://localhost:8000', 'verify' => false, 'allow_redirects' => ['track_redirects' => true]]);

        $response = $client->request('POST', '/register', [
            'form_params' => [
                'username' => 'tes',
                'email' => 'test_email@example.com',
                'password' => 'test_password',
                'password-repeat' => 'wrong_test_password'
            ]
        ]);

        $redirectionRoute = $response->getHeader(\GuzzleHttp\RedirectMiddleware::HISTORY_HEADER)[0];

        $this->assertEquals($redirectionRoute, 'https://localhost:8000/register');
    }
}