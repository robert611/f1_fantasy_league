<?php 

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use App\Model\Database\QueryBuilder;
use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\RacePredictionsRepository;
use App\Model\Auth\CreateTestUser;
use App\Model\Database\Fixtures\RacePredictionsFixtures;

class RacePredictionsControllerTest extends TestCase
{
    private QueryBuilder $queryBuilder;
    private RacePredictionsFixtures $racePredictionsFixtures;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->racePredictionsFixtures = new RacePredictionsFixtures();

        $this->racePredictionsFixtures->clear();
    }

    public function test_if_race_predictions_can_be_saved()
    {
        $client = HttpClient::create();

        $driverIds = (new DriverRepository())->findDriversIds();

        $raceId = (new RaceRepository())->findAll()[0]['id'];

        $body = $this->getRacePredictions($driverIds);

        $body['test_user'] = true;
        $body['user_roles'] = '["ROLE_USER"]';

        $response = $client->request('POST', "http://localhost:8000/race/predictions/store/$raceId", [
            'verify_peer' => false,
            'body' => $body,
        ]);

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $userId = CreateTestUser::getTestUser(roles: '["ROLE_USER"]')->getId();

        $userRacePredictions = (new RacePredictionsRepository)->findBy(['user_id' => $userId, 'race_id' => $raceId]);
        $firstDriverRacePredictions = (new RacePredictionsRepository)->findOneBy(['user_id' => $userId, 'race_id' => $raceId, 'driver_id' => $driverIds[0]]);
        $lastDriverRacePredictions = (new RacePredictionsRepository)->findOneBy(['user_id' => $userId, 'race_id' => $raceId, 'driver_id' => $driverIds[19]]);

        $this->assertEquals(count($userRacePredictions), 20);
        $this->assertEquals($firstDriverRacePredictions['position'], 1);
        $this->assertEquals($lastDriverRacePredictions['position'], 20);
    }

    public function test_if_race_predictions_can_be_updated()
    {
        $client = HttpClient::create();

        $driverIds = (new DriverRepository())->findDriversIds();

        $raceId = (new RaceRepository())->findAll()[0]['id'];

        $body = $this->getRacePredictions($driverIds);

        $body['test_user'] = true;
        $body['user_roles'] = '["ROLE_USER"]';

        $response = $client->request('POST', "http://localhost:8000/race/predictions/store/$raceId", [
            'verify_peer' => false,
            'body' => $body,
        ]);

        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $userId = CreateTestUser::getTestUser(roles: '["ROLE_USER"]')->getId();

        $userRacePredictions = (new RacePredictionsRepository)->findBy(['user_id' => $userId, 'race_id' => $raceId]);
        $firstDriverRacePredictions = (new RacePredictionsRepository)->findOneBy(['user_id' => $userId, 'race_id' => $raceId, 'driver_id' => $driverIds[0]]);

        $this->assertEquals(count($userRacePredictions), 20);
        $this->assertEquals($firstDriverRacePredictions['position'], 1);

        $body["driver_position[$driverIds[0]]"] = 2;
        $body["driver_position[$driverIds[1]]"] = 1;

        $response = $client->request('POST', "http://localhost:8000/race/predictions/store/$raceId", [
            'verify_peer' => false,
            'body' => $body,
        ]);
        
        $content = $response->getContent(); /* Request is asynchronous, it makes it wait for the response */

        $userRacePredictions = (new RacePredictionsRepository)->findBy(['user_id' => $userId, 'race_id' => $raceId]);
        $updatedfirstDriverRacePredictions = (new RacePredictionsRepository)->findOneBy(['user_id' => $userId, 'race_id' => $raceId, 'driver_id' => $driverIds[0]]);
        $updatedSecondDriverRacePredictions = (new RacePredictionsRepository)->findOneBy(['user_id' => $userId, 'race_id' => $raceId, 'driver_id' => $driverIds[1]]);

        $this->assertEquals(count($userRacePredictions), 20);
        $this->assertEquals($updatedfirstDriverRacePredictions['position'], 2);
        $this->assertEquals($updatedSecondDriverRacePredictions['position'], 1);
    }

    public function getRacePredictions(array $driverIds)
    {
        return [
            "driver_position[$driverIds[0]]" => 1,
            "driver_position[$driverIds[1]]" => 2,
            "driver_position[$driverIds[2]]" => 3,
            "driver_position[$driverIds[3]]" => 4,
            "driver_position[$driverIds[4]]" => 5,
            "driver_position[$driverIds[5]]" => 6,
            "driver_position[$driverIds[6]]" => 7,
            "driver_position[$driverIds[7]]" => 8,
            "driver_position[$driverIds[8]]" => 9,
            "driver_position[$driverIds[9]]" => 10,
            "driver_position[$driverIds[10]]" => 11,
            "driver_position[$driverIds[11]]" => 12,
            "driver_position[$driverIds[12]]" => 13,
            "driver_position[$driverIds[13]]" => 14,
            "driver_position[$driverIds[14]]" => 15,
            "driver_position[$driverIds[15]]" => 16,
            "driver_position[$driverIds[16]]" => 17,
            "driver_position[$driverIds[17]]" => 18,
            "driver_position[$driverIds[18]]" => 19,
            "driver_position[$driverIds[19]]" => 20
        ];
    }
}