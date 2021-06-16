<?php 

namespace App\Model\ResultsMatching;

use App\Model\RacePredictions\RacePoints;

class CompareResults 
{
    private array $raceResults;
    private null | int $gainedPoints = null;
    private null | array $matchedPositions = null;

    public function __construct(array $raceResults)
    {
        $this->raceResults = $raceResults;
        $this->racePointsTable = RacePoints::getRacePoints();
    }

    public function comparePredictionsToResults(array $predictionsCollection): void
    { 
        $gainedPoints = 0;
        $matchedPositions = [];

        $raceResultsToFilter = $this->raceResults;

        foreach ($predictionsCollection as $key => $prediction)
        {
            $predictedPosition = $prediction->getPosition();
            $driverActualPosition = $this->getDriverRacePosition($raceResultsToFilter, $prediction->getDriver()->getId());

            if ($predictedPosition == $driverActualPosition)
            {
                $gainedPoints += $this->racePointsTable[$predictedPosition];
                $matchedPositions[$predictedPosition] = ['matched' => true, 'predicted' => $predictedPosition, 'position' => $driverActualPosition];
            }
            else
            {
                $matchedPositions[$predictedPosition] = ['matched' => false, 'predicted' => $predictedPosition, 'position' => $driverActualPosition];
            }
        }

        $this->gainedPoints = $gainedPoints;
        $this->matchedPositions = $matchedPositions;
    }
    
    public function getGainedPoints(): int
    {
        if (is_null($this->gainedPoints)) {
            throw new \Exception('Gained Points variable is equal to null, you must call comparePredictionsToResults method first');
        }
        
        return $this->gainedPoints;
    }

    public function getMatchedPositions(): array
    {
        if (is_null($this->matchedPositions)) {
            throw new \Exception('Matched Positions variable is equal to null, you must call comparePredictionsToResults method first');
        }
        
        return $this->matchedPositions;
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

