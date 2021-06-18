<?php 

namespace App\Controller;

use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Repository\RaceResultsRepository;
use App\Model\Database\Repository\RacePredictionsRepository;
use App\Model\Database\Repository\RacePredictionsResultsRepository;
use App\Model\Database\EntityValidation\RaceResultsValidation;
use App\Model\ResultsMatching\SavePredictionsResults;
use App\Model\Database\Entity\EntityCollection;
use App\Model\Database\Entity\RaceResults;

class AdminController extends AbstractController
{
    private RaceRepository $raceRepository;
    private RaceResultsRepository $raceResultsRepository;
    private RaceResultsValidation $raceResultsValidation;
    private SavePredictionsResults $savePredictionsResults;
    private RacePredictionsResultsRepository $racePredictionsResultsRepository;

    public function __construct()
    {
        parent::__construct();

        $this->raceRepository = new RaceRepository();
        $this->driverRepository = new DriverRepository();
        $this->raceResultsRepository = new RaceResultsRepository();
        $this->raceResultsValidation = new RaceResultsValidation();
        $this->savePredictionsResults = new SavePredictionsResults();
        $this->racePredictionsResultsRepository = new RacePredictionsResultsRepository();
    }

    public function admin()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        print $this->twig->render('admin/index.html.twig');
    }

    public function raceResultsDashboard()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $races = $this->raceRepository->findAll();
        $driver = $this->driverRepository->findAll();

        $currentDate = date('Y-m-d');

        print $this->twig->render('admin/race_results.html.twig', [
            'races' => $races,
            'driver' => $driver,
            'currentDate' => $currentDate
        ]);
    }

    public function storeRaceResults()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        [$raceId, $driverId, $position] = [$this->request->get('race_id'), $this->request->get('driver_id'), $this->request->get('position')];

        if (!$this->raceResultsValidation->validateEntityData(['race_id' => $raceId, 'driver_id' => $driverId, 'position' => $position])) {
            $formErrors = $this->raceResultsValidation->getValidationErrors();

            foreach ($formErrors as $error) $this->session->getFlashBag()->add('store_race_result_error', $error);

            return $this->redirectToRoute('/admin/race/results/dashboard');
        }

        $this->raceResultsRepository->saveResult(raceId: $raceId, driverId: $driverId, position: $position);

        $this->session->getFlashBag()->add('admin_success', 'Race result has been saved');

        return $this->redirectToRoute('/admin/race/results/dashboard');
    }

    public function checkRacePredictions()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $raceId = $this->request->get('race_id');

        $race = $this->raceRepository->find($raceId);

        if (!$race) {
            $this->session->getFlashBag()->add('compare_race_results_to_predictions_error', 'This race does not exist');

            return $this->redirectToRoute('/admin/race/results/dashboard');
        }

        $this->racePredictionsResultsRepository->removeRacePredictionsResults($raceId);

        $usersRacePredictionsCollections = (new RacePredictionsRepository())->getUsersRacePredictionsCollections($raceId); 

        $raceResultsCollection = EntityCollection::getCollection((new RaceResultsRepository())->findBy(['race_id' => $raceId]), RaceResults::class);

        if (count($raceResultsCollection) < count($this->driverRepository->findAll())) {
            $this->session->getFlashBag()->add('admin_error', 'Results for this race are not entirely saved');

            return $this->redirectToRoute('/admin/race/results/dashboard');
        }

        $this->savePredictionsResults->savePredictionsResultsToDatabase($usersRacePredictionsCollections, $raceResultsCollection);

        $this->session->getFlashBag()->add('admin_success', "Predictions results were saved for " . count($usersRacePredictionsCollections) ." users");

        return $this->redirectToRoute('/admin/race/results/dashboard');
    }
}