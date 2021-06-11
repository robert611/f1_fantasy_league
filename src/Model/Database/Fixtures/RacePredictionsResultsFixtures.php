<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class RacePredictionsResultsFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [];
    }

    public function load(): void
    {
        $racePredictionsResultsRecords = $this->getRecords();

        foreach ($racePredictionsResultsRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO race_predictions_results VALUES (null, :race_id, :user_id, :points)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM race_predictions_results");
    }
}