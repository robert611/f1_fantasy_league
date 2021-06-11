<?php

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\EntityCollection;
use App\Model\Database\Entity\RacePredictions;

class RacePredictionsRepository extends AbstractRepository
{
    public string $table = 'race_predictions';

    public function savePrediction(int $raceId, int $userId, int $driverId, int $position)
    {
        $query = "INSERT INTO race_predictions VALUES (null, :race_id, :user_id, :driver_id, :position)";

        $this->queryBuilder->executeQuery($query, ['race_id' => $raceId, 'user_id' => $userId, 'driver_id' => $driverId, 'position' => $position]);
    }

    public function getUsersWithPredictionsIds(int $raceId): array
    {
        $results = $this->queryBuilder->queryWithFetchAll("SELECT DISTINCT user_id FROM race_predictions WHERE race_id = :race_id", ['race_id' => $raceId]);

        foreach ($results as $key => $result)
        {
            $results[$key] = $result['user_id'];
        }

        return $results;
    }

    public function getUsersRacePredictionsCollections(int $raceId): array
    {
        $userIds = $this->getUsersWithPredictionsIds($raceId);

        $usersPredictionsCollection = [];

        foreach ($userIds as $userId)
        {
            $userPredictions = $this->queryBuilder->queryWithFetchAll("SELECT * FROM race_predictions WHERE race_id = :race_id AND user_id = :user_id", ['race_id' => $raceId, 'user_id' => $userId]);

            $usersPredictionsCollection[$userId] = EntityCollection::getCollection($userPredictions, RacePredictions::class);
        }

        return $usersPredictionsCollection;
    }

    public function removeUserRacePredictions(int $raceId, int $userId)
    {
        $query = "DELETE FROM race_predictions WHERE race_id = :race_id AND user_id = :user_id";

        $this->queryBuilder->executeQuery($query, ['race_id' => $raceId, 'user_id' => $userId]);
    }
}