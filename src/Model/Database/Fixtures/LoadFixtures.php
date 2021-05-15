<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\UserFixtures;
use App\Model\Database\Fixtures\TeamFixtures;
use App\Model\Database\Fixtures\DriverFixtures;
use App\Model\Database\Fixtures\RaceFixtures;

class LoadFixtures
{
    public function getAppFixtures(): array
    {
        return [
            new UserFixtures(),
            new TeamFixtures(),
            new DriverFixtures(),
            new RaceFixtures()
        ];
    }

    public function load(): void 
    {
        $appFixtures = $this->getAppFixtures();

        foreach ($appFixtures as $fixture)
        {
            $fixture->load();
        }
    }

    public function clear(): void
    {
        $appFixtures = $this->getAppFixtures();

        foreach ($appFixtures as $fixture)
        {
            $fixture->clear();
        }
    }
}