<?php 

namespace App\Controller;

use App\Model\Database\Repository\RacePredictionsResultsRepository;
use App\Model\Database\Entity\EntityCollection;
use App\Model\Database\Entity\RacePredictionsResults;
use App\Model\RacePredictions\Ranking;

class ResultsController extends AbstractController
{
    private RacePredictionsResultsRepository $racePredictionsResultsRepository;
    private Ranking $ranking;

    public function __construct()
    {
        parent::__construct();

        $this->racePredictionsResultsRepository = new RacePredictionsResultsRepository();
        $this->ranking = new Ranking();
    }

    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $racesPredictionsResults = EntityCollection::getCollection($this->racePredictionsResultsRepository->findBy(['user_id' => $this->getUser()->getId()
    ]), RacePredictionsResults::class);

        $ranking = $this->ranking->getUsersRanking();

        print $this->twig->render('results/index.html.twig', ['racesPredictionsResults' => $racesPredictionsResults, 'ranking' => $ranking]);
    }
}