<?php 

namespace App\Test\Database\Repository;

use App\Model\Database\QueryBuilder;
use App\Model\Database\Repository\RacePredictionsRepository;
use App\Model\Database\Fixtures\RacePredictionsFixtures;
use PHPUnit\Framework\TestCase;

final class RacePredictionsRepositoryTest extends TestCase
{
    private $queryBuilder;
    private $racePredictionsRepository;
    private $racePredictionsFixtures;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->racePredictionsRepository = new RacePredictionsRepository();
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
}