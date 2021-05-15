<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class RaceFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [
            ['name' => 'Bahrain Grand Prix', 'race_start' => '2021-03-28'],
            ['name' => 'Emilia Romagna Grand Prix', 'race_start' => '2021-04-18'],
            ['name' => 'Portuguese Grand Prix', 'race_start' => '2021-05-02'],
            ['name' => 'Spanish Grand Prix', 'race_start' => '2021-05-09'],
            ['name' => 'Monaco Grand Prix', 'race_start' => '2021-05-23'],
            ['name' => 'Azerbaijan Grand Prix', 'race_start' => '2021-06-06'],
            ['name' => 'French Grand Prix', 'race_start' => '2021-06-20'],
            ['name' => 'Styrian Grand Prix', 'race_start' => '2021-06-27'],
            ['name' => 'Austrian Grand Prix', 'race_start' => '2021-07-04'],
            ['name' => 'British Grand Prix', 'race_start' => '2021-07-18'],
            ['name' => 'Hungarian Grand Prix', 'race_start' => '2021-08-01'],
            ['name' => 'Belgian Grand Prix', 'race_start' => '2021-08-29'],
            ['name' => 'Dutch Grand Prix', 'race_start' => '2021-09-05'],
            ['name' => 'Italian Grand Prix', 'race_start' => '2021-09-12'],
            ['name' => 'Russian Grand Prix', 'race_start' => '2021-09-26'],
            ['name' => 'Singapore Grand Prix', 'race_start' => '2021-10-03'],
            ['name' => 'Japanese Grand Prix', 'race_start' => '2021-10-10'],
            ['name' => 'United States Grand Prix', 'race_start' => '2021-10-24'],
            ['name' => 'Mexico City Grand Prix', 'race_start' => '2021-10-31'],
            ['name' => 'Sao Paulo Grand Prix', 'race_start' => '2021-11-07'],
            ['name' => 'Australian Grand Prix', 'race_start' => '2021-11-21'],
            ['name' => 'Saudi Arabian Grand Prix', 'race_start' => '2021-12-05'],
            ['name' => 'Abu Dhabi Grand Prix', 'race_start' => '2021-12-12']
        ];
    }

    public function load(): void
    {
        $raceRecords = $this->getRecords();

        foreach ($raceRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO race VALUES (null, :name, :race_start)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM race");
    }
}