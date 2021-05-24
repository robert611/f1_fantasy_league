<?php 

namespace App\Test\Database;

use App\Model\RacePredictions\SortRacePredictions;
use App\Model\Database\Entity\RacePredictions;
use PHPUnit\Framework\TestCase;

final class SortRacePredictionsTest extends TestCase
{
    private SortRacePredictions $sortRacePredictions;

    public function setUp(): void
    {
        $this->sortRacePredictions = new SortRacePredictions();
    }

    public function test_if_predictions_will_be_sorted_by_position()
    {
        $predictionsEntityCollection = [
            new RacePredictions(['id' => 1, 'race_id' => 5, 'user_id' => 23, 'driver_id' => 1, 'position' => 2]),
            new RacePredictions(['id' => 2, 'race_id' => 5, 'user_id' => 23, 'driver_id' => 2, 'position' => 1]),
            new RacePredictions(['id' => 3, 'race_id' => 5, 'user_id' => 23, 'driver_id' => 3, 'position' => 4]),
            new RacePredictions(['id' => 4, 'race_id' => 5, 'user_id' => 23, 'driver_id' => 4, 'position' => 3])
        ];

        $sortedPredictions = $this->sortRacePredictions->sortByPosition($predictionsEntityCollection);

        $this->assertEquals($sortedPredictions[0]->getPosition(), 1);
        $this->assertEquals($sortedPredictions[1]->getPosition(), 2);
        $this->assertEquals($sortedPredictions[2]->getPosition(), 3);
        $this->assertEquals($sortedPredictions[3]->getPosition(), 4);
    }
}