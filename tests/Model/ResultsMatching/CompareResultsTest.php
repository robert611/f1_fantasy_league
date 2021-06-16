<?php 

namespace App\Tests\Model\ResultsMatching;

use App\Model\ResultsMatching\CompareResults;
use App\Model\Database\Entity\RaceResults;
use App\Model\Database\Entity\RacePredictions;
use PHPUnit\Framework\TestCase;

final class CompareResultsTest extends TestCase
{
    public function test_if_results_and_predictions_can_be_compared()
    {
        $raceResults = [
            new RaceResults(['id' => 1, 'race_id' => 1, 'driver_id' => 1, 'position' => 15]),
            new RaceResults(['id' => 2, 'race_id' => 1, 'driver_id' => 2, 'position' => 2]),
            new RaceResults(['id' => 3, 'race_id' => 1, 'driver_id' => 3, 'position' => 3]),
            new RaceResults(['id' => 4, 'race_id' => 1, 'driver_id' => 4, 'position' => 5]),
            new RaceResults(['id' => 5, 'race_id' => 1, 'driver_id' => 5, 'position' => 19])
        ];

        $racePredictions = [
            new RacePredictions(['id' => 1, 'race_id' => 1, 'driver_id' => 1, 'user_id' => 1, 'position' => 15]),
            new RacePredictions(['id' => 2, 'race_id' => 1, 'driver_id' => 2, 'user_id' => 1, 'position' => 2]),
            new RacePredictions(['id' => 3, 'race_id' => 1, 'driver_id' => 3, 'user_id' => 1, 'position' => 3]),
            new RacePredictions(['id' => 4, 'race_id' => 1, 'driver_id' => 4, 'user_id' => 1, 'position' => 8]),
            new RacePredictions(['id' => 5, 'race_id' => 1, 'driver_id' => 5, 'user_id' => 1, 'position' => 1]),
        ];

        $compareResults = new CompareResults($raceResults);

        $compareResults->comparePredictionsToResults($racePredictions);

        $gainedPoints = $compareResults->getGainedPoints();

        $this->assertEquals($gainedPoints, 33);
    }

    public function test_if_gained_points_cannot_be_fetched_before_calling_compare_function()
    {
        $this->expectException(\Exception::class);

        $compareResults = new CompareResults([]);

        $points = $compareResults->getGainedPoints();
    }

    public function test_if_driver_position_can_be_found()
    {
        $raceResults = [
            new RaceResults(['id' => 1, 'race_id' => 1, 'driver_id' => 1, 'position' => 15]),
            new RaceResults(['id' => 2, 'race_id' => 1, 'driver_id' => 2, 'position' => 2]),
            new RaceResults(['id' => 3, 'race_id' => 1, 'driver_id' => 3, 'position' => 3]),
            new RaceResults(['id' => 4, 'race_id' => 1, 'driver_id' => 4, 'position' => 5]),
            new RaceResults(['id' => 5, 'race_id' => 1, 'driver_id' => 5, 'position' => 19])
        ];

        $compareResults = new CompareResults($raceResults);

        $this->assertEquals($compareResults->getDriverRacePosition($raceResults, 1), 15);
        $this->assertEquals($compareResults->getDriverRacePosition($raceResults, 2), 2);
        $this->assertEquals($compareResults->getDriverRacePosition($raceResults, 3), 3);
        $this->assertEquals($compareResults->getDriverRacePosition($raceResults, 4), 5);
        $this->assertEquals($compareResults->getDriverRacePosition($raceResults, 5), 19);
    }
}