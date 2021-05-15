<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\Fixture;

class TeamFixtures extends Fixture implements FixturesInterface
{
    public function getRecords(): array
    {
        return [
            ['id' => 1, 'name' => 'Ferrari', 'picture' => 'ferrari.png'],
            ['id' => 2, 'name' => 'Alfa Romeo', 'picture' => 'alfaromeo.png'],
            ['id' => 3, 'name' => 'Haas', 'picture' => 'haas.png'],
            ['id' => 4, 'name' => 'Mclaren', 'picture' => 'mclaren.png'],
            ['id' => 5, 'name' => 'Mercedes', 'picture' => 'mercedes.png'],
            ['id' => 6, 'name' => 'Racing Point', 'picture' => 'racingpoint.png'],
            ['id' => 7, 'name' => 'Red Bull', 'picture' => 'redbull.png'],
            ['id' => 8, 'name' => 'Renault', 'picture' => 'renault.png'],
            ['id' => 9, 'name' => 'Toro Rosso', 'picture' => 'tororosso.png'],
            ['id' => 10, 'name' => 'Williams', 'picture' => 'williams.png']
        ];
    }

    public function load(): void
    {
        $teamRecords = $this->getRecords();

        foreach ($teamRecords as $record)
        {
            $this->queryBuilder->executeQuery("INSERT INTO team VALUES (:id, :name, :picture)", $record);
        }
    }

    public function clear(): void
    {
        $this->queryBuilder->executeQuery("DELETE FROM team");
    }
}