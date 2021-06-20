<?php

namespace App\Model\Database\Repository;

class RacePredictionsResultsRepository extends AbstractRepository
{
    public string $table = 'race_predictions_results';

    public function savePredictionsResults(int $raceId, int $userId, int $points): void
    {
        $query = "INSERT INTO race_predictions_results VALUES (null, :race_id, :user_id, :points)";

        $this->queryBuilder->executeQuery($query, ['race_id' => $raceId, 'user_id' => $userId, 'points' => $points]);
    }

    public function removeRacePredictionsResults(int $raceId)
    {
        $query = "DELETE FROM race_predictions_results WHERE race_id = :race_id";

        $this->queryBuilder->executeQuery($query, ['race_id' => $raceId]);
    }

    public function getUsersWithResultsIds(): array
    {
        $results = $this->queryBuilder->queryWithFetchAll("SELECT DISTINCT user_id FROM race_predictions_results");

        foreach ($results as $key => $result)
        {
            $results[$key] = $result['user_id'];
        }

        return $results;
    }
}