<?php 

namespace App\Model\ResultsMatching;

use App\Model\RacePredictions\RacePoints;

class CompareResults 
{
    private array $raceResults;
    private null | int $gainedPoints = null;

    public function __construct(array $raceResults)
    {
        $this->raceResults = $raceResults;
        $this->racePointsTable = RacePoints::getRacePoints();
    }

    public function comparePredictionsToResults(array $predictionsCollection): void
    { 
        $gainedPoints = 0;

        $raceResultsToFilter = $this->raceResults;

        foreach ($predictionsCollection as $prediction)
        {
            $predictedPosition = $prediction->getPosition();
            $driverActualPosition = $this->getDriverRacePosition($raceResultsToFilter, $prediction->getDriver()->getId());

            if ($predictedPosition == $driverActualPosition)
            {
                $gainedPoints += $this->racePointsTable[$predictedPosition];
            }
        }

        $this->gainedPoints = $gainedPoints;
    }
    
    public function getGainedPoints(): int
    {
        if (is_null($this->gainedPoints)) {
            throw new \Exception('Gained Points variable is equal to null, you must call comparePredictionsToResults method first');
        }
        
        return $this->gainedPoints;
    }

    public function getDriverRacePosition(array &$raceResultsToFilter, int $driverId): int | false
    {
        foreach ($raceResultsToFilter as $key => $result)
        {
            if ($result->getDriverId() == $driverId)
            {
                unset($raceResultsToFilter[$key]);
                return $result->getPosition();
            }
        }

        return false;
    }
}

