<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;
use App\Model\Database\Repository\DriverRepository;

class RacePredictionsFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        $userId = $this->queryBuilder->queryWithFetch("SELECT * FROM user LIMIT 1")['id'];
        $driversIds = (new DriverRepository())->findDriversIds();

        return [
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[11], 'position' => 1],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[16], 'position' => 2],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[1], 'position' => 3],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[2], 'position' => 4],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[7], 'position' => 5],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[6], 'position' => 6],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[10], 'position' => 7],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[12], 'position' => 8],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[19], 'position' => 9],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[0], 'position' => 10],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[3], 'position' => 11],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[8], 'position' => 12],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[9], 'position' => 13],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[18], 'position' => 14],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[17], 'position' => 15],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[4], 'position' => 16],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[5], 'position' => 17],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[13], 'position' => 18],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[14], 'position' => 19],
            ['race_id' => 1, 'user_id' => $userId, 'driver_id' => $driversIds[15], 'position' => 20],
        ];
    }

    public function load(): void
    {
        $racePredictionsRecords = $this->getRecords();

        foreach ($racePredictionsRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO race_predictions VALUES (null, :race_id, :user_id, :driver_id, :position)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM race_predictions");
    }
}