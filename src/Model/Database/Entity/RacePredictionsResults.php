<?php 

namespace App\Model\Database\Entity;

class RacePredictionsResults extends Entity
{
    private int $id;
    private null | Race $race = null;
    private null | User $user = null;
    private int $points;

    private int $raceId;
    private int $userId;

    public function __construct(array $racePredictionsResults)
    {
        parent::__construct();

        $this->setId($racePredictionsResults['id']);
        $this->raceId = $racePredictionsResults['race_id'];
        $this->userId = $racePredictionsResults['user_id'];

        $this->setPosition($racePredictionsResults['points']);
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

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getPoints(): int
    {
        return $this->points;
    }
}
