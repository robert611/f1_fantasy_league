<?php

namespace App\Model\Database\Repository;

class RacePredictionsRepository extends AbstractRepository
{
    public string $table = 'race_predictions';

    public function savePrediction(int $raceId, int $userId, int $driverId, int $position)
    {
        $query = "INSERT INTO race_predictions VALUES (null, :race_id, :user_id, :driver_id, :position)";

        $this->queryBuilder->executeQuery($query, ['race_id' => $raceId, 'user_id' => $userId, 'driver_id' => $driverId, 'position' => $position]);
    }

    public function removeUserRacePredictions(int $raceId, int $userId)
    {
        $query = "DELETE FROM race_predictions WHERE race_id = :race_id AND user_id = :user_id";

        $this->queryBuilder->executeQuery($query, ['race_id' => $raceId, 'user_id' => $userId]);
    }
}