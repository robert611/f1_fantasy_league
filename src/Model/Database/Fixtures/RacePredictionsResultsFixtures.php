<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;
use App\Model\Database\Repository\UserRepository;

class RacePredictionsResultsFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        $usersIds = (new UserRepository())->findUsersIds();

        return [
            ['race_id' => 1, 'user_id' => $usersIds[0], 'points' => 26],
            ['race_id' => 1, 'user_id' => $usersIds[1], 'points' => 52],
            ['race_id' => 1, 'user_id' => $usersIds[2], 'points' => 21],
            ['race_id' => 2, 'user_id' => $usersIds[0], 'points' => 5]
        ];
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