<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;
use App\Model\Database\Repository\DriverRepository;

class RaceResultsFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        $driversIds = (new DriverRepository())->findDriversIds();

        return [
            1 => [
                ['driver_id' => $driversIds[8], 'position' => 1],
                ['driver_id' => $driversIds[13], 'position' => 2],
                ['driver_id' => $driversIds[9], 'position' => 3],
                ['driver_id' => $driversIds[7], 'position' => 4],
                ['driver_id' => $driversIds[12], 'position' => 5],
                ['driver_id' => $driversIds[0], 'position' => 6],
                ['driver_id' => $driversIds[6], 'position' => 7],
                ['driver_id' => $driversIds[1], 'position' => 8],
                ['driver_id' => $driversIds[16], 'position' => 9],
                ['driver_id' => $driversIds[11], 'position' => 10],
                ['driver_id' => $driversIds[2], 'position' => 11],
                ['driver_id' => $driversIds[3], 'position' => 12],
                ['driver_id' => $driversIds[15], 'position' => 13],
                ['driver_id' => $driversIds[18], 'position' => 14],
                ['driver_id' => $driversIds[10], 'position' => 15],
                ['driver_id' => $driversIds[4], 'position' => 16],
                ['driver_id' => $driversIds[17], 'position' => 17],
                ['driver_id' => $driversIds[19], 'position' => 18],
                ['driver_id' => $driversIds[14], 'position' => 0],
                ['driver_id' => $driversIds[5], 'position' => 0]
            ],
            2 => [
                ['driver_id' => $driversIds[13], 'position' => 1],
                ['driver_id' => $driversIds[8], 'position' => 2],
                ['driver_id' => $driversIds[7], 'position' => 3],
                ['driver_id' => $driversIds[0], 'position' => 4],
                ['driver_id' => $driversIds[1], 'position' => 5],
                ['driver_id' => $driversIds[6], 'position' => 6],
                ['driver_id' => $driversIds[17], 'position' => 7],
                ['driver_id' => $driversIds[11], 'position' => 8],
                ['driver_id' => $driversIds[15], 'position' => 9],
                ['driver_id' => $driversIds[14], 'position' => 10],
                ['driver_id' => $driversIds[12], 'position' => 11],
                ['driver_id' => $driversIds[16], 'position' => 12],
                ['driver_id' => $driversIds[2], 'position' => 13],
                ['driver_id' => $driversIds[3], 'position' => 14],
                ['driver_id' => $driversIds[10], 'position' => 15],
                ['driver_id' => $driversIds[4], 'position' => 16],
                ['driver_id' => $driversIds[5], 'position' => 17],
                ['driver_id' => $driversIds[9], 'position' => 0],
                ['driver_id' => $driversIds[18], 'position' => 0],
                ['driver_id' => $driversIds[19], 'position' => 0]
            ],
            3 => [
                ['driver_id' => $driversIds[8], 'position' => 1],
                ['driver_id' => $driversIds[13], 'position' => 2],
                ['driver_id' => $driversIds[9], 'position' => 3],
                ['driver_id' => $driversIds[12], 'position' => 4],
                ['driver_id' => $driversIds[7], 'position' => 5],
                ['driver_id' => $driversIds[0], 'position' => 6],
                ['driver_id' => $driversIds[15], 'position' => 7],
                ['driver_id' => $driversIds[14], 'position' => 8],
                ['driver_id' => $driversIds[6], 'position' => 9],
                ['driver_id' => $driversIds[17], 'position' => 10],
                ['driver_id' => $driversIds[1], 'position' => 11],
                ['driver_id' => $driversIds[3], 'position' => 12],
                ['driver_id' => $driversIds[10], 'position' => 13],
                ['driver_id' => $driversIds[11], 'position' => 14],
                ['driver_id' => $driversIds[16], 'position' => 15],
                ['driver_id' => $driversIds[18], 'position' => 16],
                ['driver_id' => $driversIds[4], 'position' => 17],
                ['driver_id' => $driversIds[19], 'position' => 18],
                ['driver_id' => $driversIds[5], 'position' => 19],
                ['driver_id' => $driversIds[2], 'position' => 0]
            ]
        ];
    }

    public function load(): void
    {
        $raceResultsRecords = $this->getRecords();

        foreach ($raceResultsRecords as $raceId => $raceResults)
        {
            foreach ($raceResults as $result)
            {
                $this->queryBuilder->executeQuery("INSERT INTO race_results VALUES (null, :race_id, :driver_id, :position)", 
                ['race_id' => $raceId, 'driver_id' => $result['driver_id'], 'position' => $result['position']]);
            }
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM race_results");
    }
}