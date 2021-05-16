<?php 

namespace App\Controller;

use App\Model\Database\Repository\DriverRepository;
use App\Model\Database\Repository\RaceRepository;

class IndexController extends AbstractController
{
    private DriverRepository $driverRepository;
    private RaceRepository $raceRepository;

    public function __construct()
    {
        parent::__construct();

        $this->driverRepository = new DriverRepository();
        $this->raceRepository = new RaceRepository();
    }

    public function home()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $drivers = $this->driverRepository->findAllWithTeams();
        $races = $this->raceRepository->findAll();


        print $this->twig->render('home.html.twig', ['drivers' => $drivers, 'races' => $races]);
    }
}