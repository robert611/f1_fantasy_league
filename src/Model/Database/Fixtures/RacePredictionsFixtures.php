<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class RacePredictionsFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [];
    }

    public function load(): void
    {
        $racePredictionsRecords = $this->getRecords();

        foreach ($racePredictionsRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO race_predictions VALUES (:id, :race_id, :user_id, :driver_id, :position)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM race_predictions");
    }
}