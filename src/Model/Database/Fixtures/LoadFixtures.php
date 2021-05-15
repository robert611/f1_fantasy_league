<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\Fixtures\UserFixtures;
use App\Model\Database\Fixtures\TeamFixtures;

class LoadFixtures
{
    public function getAppFixtures(): array
    {
        return [
            new UserFixtures(),
            new TeamFixtures()
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