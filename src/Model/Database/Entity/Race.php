<?php 

namespace App\Model\Database\Entity;

class Race extends Entity
{
    private int $id;
    private string $name;
    private string $raceStart;

    public function __construct(array $race)
    {
        parent::__construct();
        
        $this->setId($race['id']);
        $this->setName($race['name']);
        $this->setRaceStart($race['race_start']);
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
    
    public function setRaceStart(string $raceStart): void
    {
        $this->raceStart = $raceStart;
    }

    public function getRaceStart(): string
    {
        return $this->raceStart;
    }
}