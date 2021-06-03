<?php 

namespace App\Test\Database\Repository;

use App\Model\Database\QueryBuilder;
use App\Model\Database\Repository\RaceResultsRepository;
use App\Model\Database\Fixtures\RaceResultsFixtures;
use PHPUnit\Framework\TestCase;

final class RaceResultsRepositoryTest extends TestCase
{
    private $queryBuilder;
    private $raceResultsRepository;
    private $raceResultsFixtures;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->raceResultsRepository = new RaceResultsRepository();
        $this->raceResultsFixtures = new RaceResultsFixtures();
    }

    public function tearDown(): void
    {
        $this->raceResultsFixtures->clear();
        $this->raceResultsFixtures->load();
    }

    public function test_if_race_result_can_be_stored()
    {
        $this->raceResultsFixtures->clear();

        $raceId = $this->queryBuilder->queryWithFetch("SELECT * FROM race")['id'];
        $driverId = $this->queryBuilder->queryWithFetch("SELECT * FROM driver")['id'];
        $position = 1;

        $this->raceResultsRepository->saveResult(raceId: $raceId, driverId: $driverId, position: $position);

        $raceResults = $this->raceResultsRepository->findAll();

        $this->assertEquals(count($raceResults), 1);
        $this->assertEquals($raceResults[0]['race_id'], $raceId);
        $this->assertEquals($raceResults[0]['driver_id'], $driverId);
        $this->assertEquals($raceResults[0]['position'], $position);
    }
}