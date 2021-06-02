<?php 

namespace App\Model\Database\EntityValidation;

use App\Model\Database\Repository\RaceResultsRepository;
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Repository\TeamRepository;

class RaceResultsValidation implements EntityValidationInterface
{
    private array $validationErrors;
    private RaceResultsRepository $raceResultsRepository;
    private RaceRepository $raceRepository;
    private DriverRepository $driverRepository;
    private TeamRepository $teamRepository;

    public function __construct()
    {
        $this->validationErrors = [];
        $this->raceResultsRepository = new RaceResultsRepository();
        $this->raceRepository = new RaceRepository();
        $this->driverRepository = new DriverRepository();
        $this->teamRepository = new TeamRepository();
    }

    public function validateEntityData(array $entityData): bool
    {
        $driversNumber = $this->getDriversNumber();

        [$raceId, $driverId, $position] = [$entityData['race_id'], $entityData['driver_id'], $entityData['position']];

        $race = $this->raceRepository->find($raceId);
        $driver = $this->driverRepository->find($driverId);

        if ($race == false)
        {
            $this->addValidationError("Race with id: ${raceId} does not exist");
        }

        if ($driver == false)
        {
            $this->addValidationError("Driver with id: ${driverId} does not exist");
        }

        if ($position < 0 or $position > $driversNumber)
        {
            $this->addValidationError("Driver position is invalid it cannot be biger than the number of drivers(${driversNumber}) and smaller than 0, it can be 0 indicating that driver did not finish the race");
        }

        if ($this->raceResultsRepository->findOneBy(['race_id' => $raceId, 'driver_id' => $driverId]) !== false)
        {
            $driverName = $driver['name'] ?? '';
            $driverSurname = $driver['surname'] ?? '';
            $raceName = $race['name'] ?? '';

            $this->addValidationError("Driver with name: {$driverName} {$driverSurname} has his race result already added to {$raceName}");
        }

        return empty($this->getValidationErrors());
    }

    public function addValidationError(string $validationError): void
    {
        $this->validationErrors[] = $validationError;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    public function getDriversNumber(): int
    {
        $teamsNumber = count($this->teamRepository->findAll());

        return $teamsNumber * 2;
    }
}