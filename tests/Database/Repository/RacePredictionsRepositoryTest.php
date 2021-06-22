<?php 

namespace App\Tests\Database\Repository;

use PHPUnit\Framework\TestCase;
use App\Model\Database\QueryBuilder;
use App\Model\Database\Repository\RacePredictionsRepository; 
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\UserRepository;
use App\Model\Database\Fixtures\RacePredictionsFixtures;
use App\Model\Database\Entity\RacePredictions;

final class RacePredictionsRepositoryTest extends TestCase
{
    private $queryBuilder;
    private $racePredictionsRepository;
    private RaceRepository $raceRepository;
    private UserRepository $userRepository;
    private $racePredictionsFixtures;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->racePredictionsRepository = new RacePredictionsRepository();
        $this->raceRepository = new RaceRepository();
        $this->userRepository = new UserRepository();
        $this->racePredictionsFixtures = new RacePredictionsFixtures();
    }

    public function tearDown(): void
    {
        $this->racePredictionsFixtures->clear();
        $this->racePredictionsFixtures->load();
    }

    public function test_if_race_predictions_can_be_stored()
    {
        $this->racePredictionsFixtures->clear();

        $raceId = $this->queryBuilder->queryWithFetch("SELECT * FROM race")['id'];
        $userId = $this->queryBuilder->queryWithFetch("SELECT * FROM user")['id'];
        $driverId = $this->queryBuilder->queryWithFetch("SELECT * FROM driver")['id'];
        $position = 1;

        $this->racePredictionsRepository->savePrediction(raceId: $raceId, userId: $userId, driverId: $driverId, position: $position);

        $predictions = $this->racePredictionsRepository->findAll();

        $this->assertEquals(count($predictions), 1);
        $this->assertEquals($predictions[0]['race_id'], $raceId);
        $this->assertEquals($predictions[0]['user_id'], $userId);
        $this->assertEquals($predictions[0]['driver_id'], $driverId);
        $this->assertEquals($predictions[0]['position'], $position);
    }

    public function test_if_user_race_predictions_can_be_deleted()
    {
        $userPrediction = $this->racePredictionsRepository->findAll()[0];

        $this->racePredictionsRepository->removeUserRacePredictions(raceId: $userPrediction['race_id'], userId: $userPrediction['user_id']);

        $userPredictions = $this->racePredictionsRepository->findBy(['race_id' => $userPrediction['race_id'], 'user_id' => $userPrediction['user_id']]);

        $this->assertEquals(count($userPredictions), 0);
    }

    public function test_if_users_with_race_predictions_ids_can_be_found()
    {
        $raceId = $this->raceRepository->first()['id'];
        $userId = $this->userRepository->first()['id'];

        $usersWithPredictionsIds = $this->racePredictionsRepository->getUsersWithPredictionsIds($raceId);

        $this->assertEquals($usersWithPredictionsIds[0], $userId); /* According to data from fixtures */
    }

    public function test_if_users_race_predictions_collections_can_be_found()
    {
        $raceId = $this->raceRepository->first()['id'];
        $userId = $this->userRepository->first()['id'];

        $usersRacePredictionsCollections = $this->racePredictionsRepository->getUsersRacePredictionsCollections($raceId);

        /* According to data from fixtures */
        $this->assertEquals(count($usersRacePredictionsCollections), 1);
        $this->assertEquals(count($usersRacePredictionsCollections[$userId]), 20);
        $this->assertTrue($usersRacePredictionsCollections[$userId][0] instanceof RacePredictions);
    }

    public function test_if_empty_array_is_returned_if_no_race_prediction_exists()
    {        
        $this->racePredictionsFixtures->clear();

        $raceId = $this->raceRepository->first()['id'];

        $usersRacePredictionsCollections = $this->racePredictionsRepository->getUsersRacePredictionsCollections($raceId);

        $this->assertTrue(is_array($usersRacePredictionsCollections));
        $this->assertTrue(empty($usersRacePredictionsCollections));
    }
}