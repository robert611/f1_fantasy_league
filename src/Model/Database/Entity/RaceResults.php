<?php 

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Race;
use App\Model\Database\Entity\Driver;

class RaceResults extends Entity
{
    private int $id;
    private null | Race $race = null;
    private null | Driver $driver = null;
    private int $position;

    private int $raceId;
    private int $driverId;

    public function __construct(array $raceResults)
    {
        parent::__construct();

        $this->setId($raceResults['id']);
        $this->raceId = $raceResults['race_id'];
        $this->driverId = $raceResults['driver_id'];

        $this->setPosition($raceResults['position']);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;       
    }

    public function setRace(int $raceId): void
    {
        $race = $this->queryBuilder->queryWithFetch("SELECT * FROM race WHERE id = :id", ['id' => $raceId]);

        $this->race = new Race($race);
    }

    public function getRace(): Race
    {
        if (is_null($this->race))
        {
            $this->setRace($this->raceId);
        }

        return $this->race;
    }

    public function setDriver(int $driverId): void
    {
        $driver = $this->queryBuilder->queryWithFetch("SELECT * FROM driver WHERE id = :id", ['id' => $driverId]);

        $this->driver = new Driver($driver);
    }

    public function getDriver(): Driver
    {
        if (is_null($this->driver))
        {
            $this->setDriver($this->driverId);
        }
        
        return $this->driver;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}