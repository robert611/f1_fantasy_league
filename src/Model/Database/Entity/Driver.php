<?php 

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Team;

class Driver extends Entity
{
    private int $id;
    private string $name;
    private string $surname;
    private int $driverNumber;
    private int $teamId;
    private null | Team $team = null;

    public function __construct(array $driver)
    {
        parent::__construct();
        
        $this->setId($driver['id']);
        $this->setName($driver['name']);
        $this->setSurname($driver['surname']);
        $this->setDriverNumber($driver['driver_number']);
        $this->teamId = $driver['team_id'];
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;       
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;       
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getSurname(): string
    {
        return $this->surname;       
    }

    public function setDriverNumber(int $driverNumber): void
    {
        $this->driverNumber = $driverNumber;
    }

    public function getDriverNumber(): int
    {
        return $this->driverNumber;       
    }

    public function setTeam(int $teamId): void
    {
        $team = $this->queryBuilder->queryWithFetch("SELECT * FROM team WHERE id = :id", ['id' => $teamId]);

        $this->team = new Team($team);
    }

    public function getTeam(): Team
    {
        if (is_null($this->team))
        {
            $this->setTeam($this->teamId);
        }

        return $this->team;
    }
}