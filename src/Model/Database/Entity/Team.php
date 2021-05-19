<?php 

namespace App\Model\Database\Entity;

class Team extends Entity
{
    private int $id;
    private string $name;
    private null | string $picture;

    public function __construct(array $team)
    {
        parent::__construct();
        
        $this->setId($team['id']);
        $this->setName($team['name']);
        $this->setPicture($team['picture']);
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

    public function setPicture(null | string $picture): void
    {
        $this->picture = $picture;
    }

    public function getPicture(): null | string
    {
        return $this->picture;
    }
}