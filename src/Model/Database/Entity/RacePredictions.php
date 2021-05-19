<?php 

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Race;
use App\Model\Auth\User;
use App\Model\Database\Entity\Driver;

class RacePredictions extends Entity
{
    private int $id;
    private null | Race $race = null;
    private null | User $user = null;
    private null | Driver $driver = null;
    private int $position;

    private int $raceId;
    private int $userId;
    private int $driverId;

    public function __construct(array $racePredictions)
    {
        parent::__construct();

        $this->setId($racePredictions['id']);
        $this->raceId = $racePredictions['race_id'];
        $this->userId = $racePredictions['user_id'];
        $this->driverId = $racePredictions['driver_id'];

        $this->setPosition($racePredictions['position']);
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

    public function setUser(int $userId): void
    {
        $user = $this->queryBuilder->queryWithFetch("SELECT * FROM user WHERE id = :id", ['id' => $userId]);

        $this->user = new User($user);
    }

    public function getUser(): User
    {
        if (is_null($this->user))
        {
            $this->setUser($this->userId);
        }

        return $this->user;
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