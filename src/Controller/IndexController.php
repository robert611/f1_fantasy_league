<?php 

namespace App\Controller;

use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\RacePredictionsRepository;
use App\Model\Database\Entity\RacePredictions;
use App\Model\Database\Entity\Driver;
use App\Model\Database\Entity\Race;
use App\Model\Database\Entity\EntityCollection;
use App\Model\RacePredictions\DefaultRacePredictions;
use App\Model\RacePredictions\SortRacePredictions;
use App\Model\RacePredictions\RacePoints;

class IndexController extends AbstractController
{
    private DriverRepository $driverRepository;
    private RaceRepository $raceRepository;
    private RacePredictionsRepository $racePredictionsRepository;

    public function __construct()
    {
        parent::__construct();

        $this->driverRepository = new DriverRepository();
        $this->raceRepository = new RaceRepository();
        $this->racePredictionsRepository = new RacePredictionsRepository();
    }

    public function home($raceId = null)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $drivers = EntityCollection::getCollection($this->driverRepository->findAll(), Driver::class);
        $races = EntityCollection::getCollection($this->raceRepository->findAll(), Race::class);

        if (!$race = $this->raceRepository->find((int) $raceId)) 
        {
            $raceId = $races[0]->getId();
            $race = $this->raceRepository->find((int) $raceId);
        }

        $raceId = (int) $raceId;
        
        $currentRace = $this->raceRepository->find($raceId);

        $userId = $this->getUser()->getId();

        $currentRacePredictions = EntityCollection::getCollection($this->racePredictionsRepository->findBy(['user_id' => $userId, 'race_id' => $raceId]), RacePredictions::class);

        $defaultStandings = false;

        if (empty($currentRacePredictions))
        {
            $currentRacePredictions = DefaultRacePredictions::getDefaultRacePredictions($drivers, $raceId, $userId);
            $defaultStandings = true;
        }
        else
        {
            $currentRacePredictions = SortRacePredictions::sortByPosition($currentRacePredictions);
        }

        $currentDate = date('Y-m-d');

        print $this->twig->render('home.html.twig', [
            'race' => $race,
            'races' => $races,
            'currentRace' => $currentRace,
            'currentRacePredictions' => $currentRacePredictions,
            'racePoints' => RacePoints::getRacePoints(),
            'defaultStandings' => $defaultStandings,
            'currentDate' => $currentDate
        ]);
    }
}