<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class DriverFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [
            ['team_id' => 1, 'name' => 'Charles', 'surname' => 'Leclerc', 'driver_number' => 16],
            ['team_id' => 1, 'name' => 'Carlos', 'surname' => 'Sainz', 'driver_number' => 55],
            ['team_id' => 2, 'name' => 'Kimi', 'surname' => 'Raikonnen', 'driver_number' => 7],
            ['team_id' => 2, 'name' => 'Antonio', 'surname' => 'Giovinazzi', 'driver_number' => 99],
            ['team_id' => 3, 'name' => 'Mick', 'surname' => 'Schumacher', 'driver_number' => 47],
            ['team_id' => 3, 'name' => 'Nikita', 'surname' => 'Mazepin', 'driver_number' => 9],
            ['team_id' => 4, 'name' => 'Daniel', 'surname' => 'Ricciardo', 'driver_number' => 3],
            ['team_id' => 4, 'name' => 'Lando', 'surname' => 'Norris', 'driver_number' => 4],
            ['team_id' => 5, 'name' => 'Lewis', 'surname' => 'Hamilton', 'driver_number' => 44],
            ['team_id' => 5, 'name' => 'Valteri', 'surname' => 'Bottas', 'driver_number' => 77],
            ['team_id' => 6, 'name' => 'Sebastian', 'surname' => 'Vettel', 'driver_number' => 5],
            ['team_id' => 6, 'name' => 'Lance', 'surname' => 'Stroll', 'driver_number' => 18],
            ['team_id' => 7, 'name' => 'Sergio', 'surname' => 'Perez', 'driver_number' => 11],
            ['team_id' => 7, 'name' => 'Max', 'surname' => 'Verstappen', 'driver_number' => 33],
            ['team_id' => 8, 'name' => 'Fernando', 'surname' => 'Alonso', 'driver_number' => 14],
            ['team_id' => 8, 'name' => 'Esteban', 'surname' => 'Ocon', 'driver_number' => 31],
            ['team_id' => 9, 'name' => 'Yuki', 'surname' => 'Tsunoda', 'driver_number' => 22],
            ['team_id' => 9, 'name' => 'Pierre', 'surname' => 'Gasly', 'driver_number' => 10],
            ['team_id' => 10, 'name' => 'George', 'surname' => 'Russell', 'driver_number' => 63],
            ['team_id' => 10, 'name' => 'Nicolas', 'surname' => 'Latifi', 'driver_number' => 6]
        ];
    }

    public function load(): void
    {
        $teamRecords = $this->getRecords();

        foreach ($teamRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO driver VALUES (null, :name, :surname, :driver_number, :team_id)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM driver");
    }
}