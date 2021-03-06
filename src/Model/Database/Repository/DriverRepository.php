<?php

namespace App\Model\Database\Repository;

class DriverRepository extends AbstractRepository
{
    public string $table = 'driver';

    public function findAllWithTeams(): array
    {
        $query = "SELECT driver.id, driver.name, driver.surname, driver.driver_number, driver.team_id, team.name as team_name, team.picture as team_picture FROM driver, team WHERE driver.team_id = team.id";

        return $this->queryBuilder->queryWithFetchAll($query);
    }
    
    public function findDriversIds(): array
    {
        return $this->queryBuilder->queryWithFetchAll(query: "SELECT id FROM driver", format: \PDO::FETCH_COLUMN);
    }
}