<?php 

namespace App\Tests\Database\EntityValidation;

use App\Model\Database\EntityValidation\RaceResultsValidation;
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Fixtures\RaceResultsFixtures;
use PHPUnit\Framework\TestCase;
use App\Model\Database\QueryBuilder;

final class RaceResultsValidationTest extends TestCase
{
    private RaceResultsValidation $raceResultsValidation;
    private RaceRepository $raceRepository;
    private DriverRepository $driverRepository;
    private QueryBuilder $queryBuilder;
    private RaceResultsFixtures $raceResultsFixtures;
    private array $raceResultData;

    public function setUp(): void
    {
        $this->raceResultsValidation = new RaceResultsValidation();
        $this->raceRepository = new RaceRepository();
        $this->driverRepository = new DriverRepository();
        $this->queryBuilder = new QueryBuilder();
        $this->raceResultsFixtures = new RaceResultsFixtures();

        $this->raceResultData['race_id'] = $this->raceRepository->findAll()[0]['id'];
        $this->raceResultData['driver_id'] =  $this->driverRepository->findAll()[0]['id'];
        $this->raceResultData['position'] = 20;
        
        $this->raceResultsFixtures->clear();
        $this->raceResultsFixtures->load();
    }

    public function tearDown(): void
    {
        $this->raceResultsFixtures->clear();
        $this->raceResultsFixtures->load();
    }

    public function test_if_race_must_exist()
    {
        $races = (new RaceRepository())->findAll();

        $fakeRaceId = (int) $races[count($races) - 1]['id'] + 1;

        $raceResultData = $this->raceResultData;
        $raceResultData['race_id'] = $fakeRaceId;

        $isValid = $this->raceResultsValidation->validateEntityData($raceResultData);

        $errors = $this->raceResultsValidation->getValidationErrors();

        $this->assertFalse($isValid);
        $this->assertEquals(count($errors), 1);
        $this->assertEquals($errors[0], "Race with id: ${fakeRaceId} does not exist");
    }

    public function test_if_driver_must_exist()
    {
        $drivers = (new DriverRepository())->findAll();

        $fakeDriverId = (int) $drivers[count($drivers) - 1]['id'] + 1;

        $raceResultData = $this->raceResultData;
        $raceResultData['driver_id'] = $fakeDriverId;

        $isValid = $this->raceResultsValidation->validateEntityData($raceResultData);

        $errors = $this->raceResultsValidation->getValidationErrors();

        $this->assertFalse($isValid);
        $this->assertEquals(count($errors), 1);
        $this->assertEquals($errors[0], "Driver with id: ${fakeDriverId} does not exist");
    }

    public function test_if_driver_position_cannot_be_smaller_than_zero()
    {
        $this->raceResultsFixtures->clear();

        $raceResultData = $this->raceResultData;
        $raceResultData['position'] = -1;

        $isValid = $this->raceResultsValidation->validateEntityData($raceResultData);

        $errors = $this->raceResultsValidation->getValidationErrors();

        $driversNumber = $this->raceResultsValidation->getDriversNumber();

        $this->assertFalse($isValid);
        $this->assertEquals(count($errors), 1);
        $this->assertEquals($errors[0], "Driver position is invalid it cannot be biger than the number of drivers(${driversNumber}) and smaller than 0, it can be 0 indicating that driver did not finish the race");
    }

    public function test_if_driver_position_cannot_be_bigger_than_numbers_of_drivers()
    {
        $this->raceResultsFixtures->clear();

        $driversNumber = $this->raceResultsValidation->getDriversNumber();

        $raceResultData = $this->raceResultData;
        $raceResultData['position'] = $driversNumber + 1;

        $isValid = $this->raceResultsValidation->validateEntityData($raceResultData);

        $errors = $this->raceResultsValidation->getValidationErrors();

        $this->assertFalse($isValid);
        $this->assertEquals(count($errors), 1);
        $this->assertEquals($errors[0], "Driver position is invalid it cannot be biger than the number of drivers(${driversNumber}) and smaller than 0, it can be 0 indicating that driver did not finish the race");
    }

    public function test_if_race_result_cannot_already_exist()
    {
        /* There already is one of Leclerc in Bahrain */
        $race = (new RaceRepository())->find($this->raceResultData['race_id']);
        $driver = (new DriverRepository())->find($this->raceResultData['driver_id']);

        $isValid = $this->raceResultsValidation->validateEntityData($this->raceResultData);

        $errors = $this->raceResultsValidation->getValidationErrors();
        
        $this->assertFalse($isValid);
        $this->assertEquals(count($errors), 1);
        $this->assertEquals($errors[0], "Driver with name: {$driver['name']} {$driver['surname']} has his race result already added to {$race['name']}");
    }
}