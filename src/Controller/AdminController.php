<?php 

namespace App\Controller;

use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Repository\RaceResultsRepository;
use App\Model\Database\EntityValidation\RaceResultsValidation;

class AdminController extends AbstractController
{
    private RaceResultsRepository $raceResultsRepository;
    private RaceResultsValidation $raceResultsValidation;

    public function __construct()
    {
        parent::__construct();

        $this->raceResultsRepository = new RaceResultsRepository();
        $this->raceResultsValidation = new RaceResultsValidation();
    }

    public function admin()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        print $this->twig->render('admin/index.html.twig');
    }

    public function raceResultsDashboard()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $races = (new RaceRepository())->findAll();
        $driver = (new DriverRepository())->findAll();

        print $this->twig->render('admin/race_results.html.twig', [
            'races' => $races,
            'driver' => $driver
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
}