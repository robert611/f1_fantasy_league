<?php 

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NativeHttpClient;
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Repository\RaceResultsRepository;
use App\Model\Database\Fixtures\RaceResultsFixtures;

class AdminControllerTest extends TestCase
{
    private RaceResultsFixtures $raceResultsFixtures;
    private NativeHttpClient $client;
    private RaceResultsRepository $raceResultsRepository;
    private $race;
    private $driver;

    public function setUp(): void
    {
        $this->client = HttpClient::create();
        $this->raceResultsFixtures = new RaceResultsFixtures();
        $this->raceResultsRepository = new RaceResultsRepository();

        $this->driver = (new DriverRepository)->findAll()[0];
        $this->race = (new RaceRepository)->findAll()[0];
    }

    /**
    * @dataProvider urlsProvider
    */
    public function test_if_index_is_successful($url, $pageElement)
    {
        $response = $this->client->request('GET', $url, [
            'verify_peer' => false,
            'headers' => ['test_user' => true, 'user_roles' => '["ROLE_ADMIN"]']
        ]);

        $statusCode = $response->getStatusCode();

        $responseContent = $response->getContent();

        $this->assertEquals($statusCode, 200);
        $this->assertTrue(str_contains($responseContent, $pageElement));
    }

    /**
    * @dataProvider urlsProvider
    */
    public function test_if_not_admin_will_be_redirected_back($url)
    {
        $response = $this->client->request('GET', $url, [
            'verify_peer' => false,
            'headers' => ['test_user' => true, 'user_roles' => '["ROLE_USER"]']
        ]);

        $responseContent = $response->getContent();

        $driverName = $this->driver['name'] . " " . $this->driver['surname'];
        
        $this->assertTrue(str_contains($responseContent, 'Make your predictions!'));
        $this->assertTrue(str_contains($responseContent, $driverName));
        $this->assertTrue(str_contains($responseContent, $this->race['name']));
    }

    public function test_if_race_result_can_be_added()
    {
        $this->raceResultsFixtures->clear();

        $body = [
            'race_id' => $this->race['id'],
            'driver_id' => $this->driver['id'],
            'position' => 17
        ];

        $response = $this->client->request('POST', 'http://localhost:8000/admin/race/results/store', [
            'verify_peer' => false,
            'headers' => ['test_user' => true, 'user_roles' => '["ROLE_ADMIN"]'],
            'body' => $body
        ]);

        $responseContent = $response->getContent();

        $raceResults = $this->raceResultsRepository->findAll();

        $this->assertTrue(str_contains($responseContent, '<h2 class="mb-4 mt-3">Add Race Result</h2>'));
        $this->assertEquals(count($raceResults), 1);
        $this->assertEquals($raceResults[0]['race_id'], $body['race_id']);
        $this->assertEquals($raceResults[0]['driver_id'], $body['driver_id']);
        $this->assertEquals($raceResults[0]['position'], $body['position']);

        $this->raceResultsFixtures->clear();
        $this->raceResultsFixtures->load();
    }

    public function urlsProvider()
    {
        return [
            ['http://localhost:8000/admin', '<a href="/admin" class="undecorate-link">Dashboard</a>'],
            ['http://localhost:8000/admin/race/results/dashboard', '<h2 class="mb-4 mt-3">Add Race Result</h2>']
        ];
    }
}