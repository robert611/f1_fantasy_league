<?php 

namespace App\Tests\Controller;

use App\Model\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class RegistrationControllerTest extends TestCase
{
    private QueryBuilder $queryBuilder;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function test_if_registration_form_can_be_used()
    {
        $client = HttpClient::create();

        $response = $client->request('GET', 'http://localhost:8000/register', [
            'verify_peer' => false,
        ]);

        $responseBody = $response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(str_contains($responseBody, 'Register'));
    }

    /**
     * @runInSeparateProcess
     */
    public function test_if_user_can_register()
    {        
        $client = HttpClient::create();

        $formFields = [
            'username' => 'test_username_x',
            'email' => 'test_email2@example.com',
            'password' => 'test_password',
            'password-repeat' => 'test_password'
        ];

        $response = $client->request('POST', 'http://localhost:8000/register', [
            'verify_peer' => false,
            'body' => $formFields
        ]);

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $user = $this->queryBuilder->queryWithFetch("SELECT * FROM user WHERE email = :email", ['email' => $formFields['email']]);
        
        $this->assertEquals($user['username'], $formFields['username']);  
        $this->assertEquals($user['email'], $formFields['email']);
        $this->assertTrue(password_verify($formFields['password'], $user['password']));

        $this->queryBuilder->executeQuery("DELETE FROM user WHERE email = :email", ['email' => $formFields['email']]);
    }

    public function test_if_user_will_not_be_registered_in_case_of_failed_registration()
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'http://localhost:8000/register', [
                'body' => [
                    'username' => 'tes',
                    'email' => 'test_email_test@example.com',
                    'password' => 'test_password',
                    'password-repeat' => 'test_password'
                ],
                'verify_peer' => false
            ]      
        );

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $user = $this->queryBuilder->queryWithFetch("SELECT * FROM user where username = :username", ['username' => 'tes']);
        
        $this->assertFalse($user);
    }

    public function test_if_user_will_be_redirected_to_register_page_after_failed_registration()
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'http://localhost:8000/register', [
                'body' => [
                    'username' => 'tes',
                    'email' => 'test_email@example.com',
                    'password' => 'test_password',
                    'password-repeat' => 'wrong_test_password'
                ],
                'verify_peer' => false
            ]
        );

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $httpLogs = $response->getInfo('debug');

        $this->assertTrue(str_contains($httpLogs, 'Location: /register'));
    }
}