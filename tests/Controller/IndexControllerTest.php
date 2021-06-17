<?php 

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Fixtures\LoadFixtures;

class IndexControllerTest extends TestCase
{
    private LoadFixtures $fixtures;

    public function setUp(): void
    {
        $this->fixtures = new LoadFixtures();

        $this->fixtures->clear();
        $this->fixtures->load();
    }

    public function test_if_homepage_is_successfull()
    {
        $client = HttpClient::create();

        $response = $client->request('GET', "http://localhost:8000/", [
            'verify_peer' => false,
            'headers' => ['test_user' => true, 'user_roles' => '["ROLE_USER"]']
        ]);

        $statusCode = $response->getStatusCode();

        $responseContent = $response->getContent();

        $driver = (new DriverRepository)->findAll()[0];

        $driverName = $driver['name'] . " " . $driver['surname'];
        
        $this->assertEquals($statusCode, 200);
        $this->assertTrue(str_contains($responseContent, 'Make your predictions!'));
        $this->assertTrue(str_contains($responseContent, $driverName));
        $this->assertFalse(str_contains($responseContent, '<form method="POST" action="/login" id="login-form">'));
    }

    public function test_if_unlogged_user_will_be_redirected()
    {
        $client = HttpClient::create();

        $response = $client->request('GET', "http://localhost:8000/", [
            'verify_peer' => false,
        ]);

        $responseContent = $response->getContent();

        $this->assertTrue(str_contains($responseContent, '<form method="POST" action="/login" id="login-form">'));
    }

    public function test_if_correct_race_will_be_displayed()
    {
        $client = HttpClient::create();

        $races = (new RaceRepository())->findAll();

        $lastRaceId = $races[count($races) - 1]['id'];
        
        $response = $client->request('GET', "http://localhost:8000/$lastRaceId", [
            'verify_peer' => false,
            'headers' => ['test_user' => true, 'user_roles' => '["ROLE_USER"]']
        ]);

        $responseContent = $response->getContent();

        $expectedString = '<a href="/'. $lastRaceId . '" class="text-decoration-none text-white">';

        $this->assertTrue(str_contains($responseContent, $expectedString));
    }

    public function test_if_unexisted_race_id_will_display_first_race()
    {
        $client = HttpClient::create();

        $races = (new RaceRepository())->findAll();

        $lastRace = $races[count($races) - 1];

        $fakeRaceId = (int) $lastRace['id'] + 1;

        $response = $client->request('GET', "http://localhost:8000/$fakeRaceId", [
            'verify_peer' => false,
            'headers' => ['test_user' => true, 'user_roles' => '["ROLE_USER"]']
        ]);

        $responseContent = $response->getContent();

        $trimedResponseContent = str_replace(' ', '', $responseContent);
        $trimedResponseContent = str_replace(array("\r\n", "\r", "\n"), '', $trimedResponseContent);

        $this->assertTrue(str_contains($responseContent, 'Make your predictions!'));
        $this->assertTrue(
            str_contains($responseContent, 'Save current predictions') or 
            str_contains($responseContent, 'It is the day of the race, you cannot make or change predictions for this race anymore')
        );
        $this->assertTrue(str_contains($trimedResponseContent, '<trclass="active-race-table-row"><td>1</td>'));
    }
}