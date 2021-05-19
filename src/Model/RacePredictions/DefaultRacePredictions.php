<?php 

namespace App\Model\RacePredictions;

use App\Model\Database\Entity\RacePredictions;

class DefaultRacePredictions
{
    public static function getDefaultRacePredictions(array $drivers, int $raceId, int $userId): array
    {
        $defaultRacePredictions = array();

        foreach ($drivers as $key => $driver)
        {
            $racePrediction = [
                'id' => $key + 1,
                'race_id' => $raceId,
                'user_id' => $userId,
                'driver_id' => $driver->getId(),
                'position' => $key + 1
            ];

            $defaultRacePredictions[] = new RacePredictions($racePrediction);
        }

        return $defaultRacePredictions;
    }
}