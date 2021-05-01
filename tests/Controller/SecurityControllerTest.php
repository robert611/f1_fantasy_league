<?php 

namespace App\Tests\Controller;

use App\Model\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use App\Model\Database\Fixtures\UserFixtures;

class SecurityControllerTest extends TestCase
{
    private QueryBuilder $queryBuilder;
    private UserFixtures $userFixtures;
    private object $client;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->userFixtures = new UserFixtures();
        $this->client = HttpClient::create();
        
        $this->userFixtures->clear();
        $this->userFixtures->load();
    }

    public function test_if_login_form_can_be_used()
    {
        $response = $this->client->request('GET', 'http://localhost:8000/login', [
            'verify_peer' => false,
        ]);

        $responseBody = $response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(str_contains($responseBody, 'Login'));
        $this->assertTrue(str_contains($responseBody, 'Username'));
        $this->assertTrue(str_contains($responseBody, 'Password'));
    }

    public function test_if_user_can_log_in()
    {
        $user = $this->userFixtures->getUserRecords()[0];

        $response = $this->client->request('POST', 'http://localhost:8000/login', [
            'body' => [
                'username' => $user['username'],
                'password' => $user['raw_password'],
            ],
            'verify_peer' => false
        ]);

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $httpLogs = $response->getInfo('debug');

        $this->assertTrue(str_contains($httpLogs, 'Location: /'));
        $this->assertTrue(!str_contains($httpLogs, 'Location: /login'));
    }

    public function test_if_user_will_be_redirected_to_login_page_after_giving_wrong_username()
    {
        $response = $this->client->request('POST', 'http://localhost:8000/login', [
            'body' => [
                'username' => 'i_do_not_exist',
                'password' => 'test_password',
            ],
            'verify_peer' => false
        ]);

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $httpLogs = $response->getInfo('debug');

        $this->assertTrue(str_contains($httpLogs, 'Location: /login'));
    }

    public function test_if_user_will_be_redirected_to_login_page_after_giving_wrong_password()
    {
        $user = $this->userFixtures->getUserRecords()[0];

        $response = $this->client->request('POST', 'http://localhost:8000/login', [
            'body' => [
                'username' => $user['username'],
                'password' => 'wrong_test_password_k',
            ],
            'verify_peer' => false
        ]);

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $httpLogs = $response->getInfo('debug');

        $this->assertTrue(str_contains($httpLogs, 'Location: /login'));
    }
}