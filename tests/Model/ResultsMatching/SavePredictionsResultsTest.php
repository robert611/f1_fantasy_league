<?php 

namespace App\Tests\Model\ResultsMatching;

use PHPUnit\Framework\TestCase;
use App\Model\ResultsMatching\SavePredictionsResults;
use App\Model\Database\Entity\RaceResults;
use App\Model\Database\Entity\RacePredictions;
use App\Model\Database\Repository\RacePredictionsResultsRepository;
use App\Model\Database\Fixtures\RacePredictionsResultsFixtures;
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\UserRepository;

final class SavePredictionsResultsTest extends TestCase
{
    private RacePredictionsResultsRepository $racePredictionsResultsRepository;
    private RacePredictionsResultsFixtures $racePredictionsResultsFixtures;
    private RaceRepository $raceRepository;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->racePredictionsResultsRepository = new RacePredictionsResultsRepository();
        $this->racePredictionsResultsFixtures = new RacePredictionsResultsFixtures();
        $this->raceRepository = new RaceRepository();
        $this->userRepository = new UserRepository();
    }

    public function test_if_predictions_results_can_be_saved_to_database()
    {
        $this->racePredictionsResultsFixtures->clear();

        $raceId = $this->raceRepository->findAll()[0]['id'];
        $users = $this->userRepository->findAll();

        $firstUserId = $users[0]['id'];
        $secondUserId = $users[1]['id'];

        $raceResults = [
            new RaceResults(['id' => 1, 'race_id' => $raceId, 'driver_id' => 1, 'position' => 15]),
            new RaceResults(['id' => 2, 'race_id' => $raceId, 'driver_id' => 2, 'position' => 2]),
            new RaceResults(['id' => 3, 'race_id' => $raceId, 'driver_id' => 3, 'position' => 3]),
            new RaceResults(['id' => 4, 'race_id' => $raceId, 'driver_id' => 4, 'position' => 5]),
            new RaceResults(['id' => 5, 'race_id' => $raceId, 'driver_id' => 5, 'position' => 19])
        ];

        $racePredictionsCollection = [
            $firstUserId => [
                new RacePredictions(['id' => 1, 'race_id' => $raceId, 'driver_id' => 1, 'user_id' => $firstUserId, 'position' => 15]),
                new RacePredictions(['id' => 2, 'race_id' => $raceId, 'driver_id' => 2, 'user_id' => $firstUserId, 'position' => 2]),
                new RacePredictions(['id' => 3, 'race_id' => $raceId, 'driver_id' => 3, 'user_id' => $firstUserId, 'position' => 3]),
                new RacePredictions(['id' => 4, 'race_id' => $raceId, 'driver_id' => 4, 'user_id' => $firstUserId, 'position' => 8]),
                new RacePredictions(['id' => 5, 'race_id' => $raceId, 'driver_id' => 5, 'user_id' => $firstUserId, 'position' => 1]),
            ],
            $secondUserId => [
                new RacePredictions(['id' => 1, 'race_id' => $raceId, 'driver_id' => 1, 'user_id' => $secondUserId, 'position' => 20]),
                new RacePredictions(['id' => 2, 'race_id' => $raceId, 'driver_id' => 2, 'user_id' => $secondUserId, 'position' => 9]),
                new RacePredictions(['id' => 3, 'race_id' => $raceId, 'driver_id' => 3, 'user_id' => $secondUserId, 'position' => 10]),
                new RacePredictions(['id' => 4, 'race_id' => $raceId, 'driver_id' => 4, 'user_id' => $secondUserId, 'position' => 5]),
                new RacePredictions(['id' => 5, 'race_id' => $raceId, 'driver_id' => 5, 'user_id' => $secondUserId, 'position' => 19]),
            ]
        ];

        $savePredictionsResults = new SavePredictionsResults();

        $savePredictionsResults->savePredictionsResultsToDatabase($racePredictionsCollection, $raceResults);

        $results = $this->racePredictionsResultsRepository->findAll();

        $this->assertEquals(count($results), 2);
        $this->assertEquals($results[0]['user_id'], $firstUserId);
        $this->assertEquals($results[1]['user_id'], $secondUserId);
        $this->assertEquals($results[0]['points'], 33);
        $this->assertEquals($results[1]['points'], 10);
        $this->assertEquals($results[0]['race_id'], $raceId);
        $this->assertEquals($results[1]['race_id'], $raceId);

        $this->racePredictionsResultsFixtures->clear();
        $this->racePredictionsResultsFixtures->load();
    }
}