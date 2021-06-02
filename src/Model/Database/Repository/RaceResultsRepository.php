<?php

namespace App\Model\Database\Repository;

class RaceResultsRepository extends AbstractRepository
{
    public string $table = 'race_results';

    public function saveResult(int $raceId, int $driverId, int $position)
    {
        $query = "INSERT INTO race_results VALUES (null, :race_id, :driver_id, :position)";

        $this->queryBuilder->executeQuery($query, ['race_id' => $raceId, 'driver_id' => $driverId, 'position' => $position]);
    }
}