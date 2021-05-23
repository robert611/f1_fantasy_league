<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class RaceFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [
            ['id' => 1, 'name' => 'Bahrain Grand Prix', 'race_start' => '2021-03-28'],
            ['id' => 2, 'name' => 'Emilia Romagna Grand Prix', 'race_start' => '2021-04-18'],
            ['id' => 3, 'name' => 'Portuguese Grand Prix', 'race_start' => '2021-05-02'],
            ['id' => 4, 'name' => 'Spanish Grand Prix', 'race_start' => '2021-05-09'],
            ['id' => 5, 'name' => 'Monaco Grand Prix', 'race_start' => '2021-05-23'],
            ['id' => 6, 'name' => 'Azerbaijan Grand Prix', 'race_start' => '2021-06-06'],
            ['id' => 7, 'name' => 'French Grand Prix', 'race_start' => '2021-06-20'],
            ['id' => 8, 'name' => 'Styrian Grand Prix', 'race_start' => '2021-06-27'],
            ['id' => 9, 'name' => 'Austrian Grand Prix', 'race_start' => '2021-07-04'],
            ['id' => 10, 'name' => 'British Grand Prix', 'race_start' => '2021-07-18'],
            ['id' => 11, 'name' => 'Hungarian Grand Prix', 'race_start' => '2021-08-01'],
            ['id' => 12, 'name' => 'Belgian Grand Prix', 'race_start' => '2021-08-29'],
            ['id' => 13, 'name' => 'Dutch Grand Prix', 'race_start' => '2021-09-05'],
            ['id' => 14, 'name' => 'Italian Grand Prix', 'race_start' => '2021-09-12'],
            ['id' => 15, 'name' => 'Russian Grand Prix', 'race_start' => '2021-09-26'],
            ['id' => 16, 'name' => 'Singapore Grand Prix', 'race_start' => '2021-10-03'],
            ['id' => 17, 'name' => 'Japanese Grand Prix', 'race_start' => '2021-10-10'],
            ['id' => 18, 'name' => 'United States Grand Prix', 'race_start' => '2021-10-24'],
            ['id' => 19, 'name' => 'Mexico City Grand Prix', 'race_start' => '2021-10-31'],
            ['id' => 20, 'name' => 'Sao Paulo Grand Prix', 'race_start' => '2021-11-07'],
            ['id' => 21, 'name' => 'Australian Grand Prix', 'race_start' => '2021-11-21'],
            ['id' => 22, 'name' => 'Saudi Arabian Grand Prix', 'race_start' => '2021-12-05'],
            ['id' => 23, 'name' => 'Abu Dhabi Grand Prix', 'race_start' => '2021-12-12']
        ];
    }

    public function load(): void
    {
        $raceRecords = $this->getRecords();

        foreach ($raceRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO race VALUES (:id, :name, :race_start)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM race");
    }
}