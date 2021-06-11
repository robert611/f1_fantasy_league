<?php 

namespace App\Controller;

use App\Model\Database\Repository\RaceRepository;
use App\Model\Database\Repository\RacePredictionsRepository;

class RacePredictionsController extends AbstractController
{
    private RaceRepository $raceRepository;
    private RacePredictionsRepository $racePredictionsRepository;

    public function __construct()
    {
        parent::__construct();

        $this->raceRepository = new RaceRepository();
        $this->racePredictionsRepository = new RacePredictionsRepository();
    }

    public function storeRacePredictions($raceId)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$race = $this->raceRepository->find((int) $raceId)) 
        {
            $this->session->getFlashBag()->add('error', 'You tried to predict results of the race that does not exist');

            return $this->redirectToRoute('/');
        }

        $currentDate = date('Y-m-d');

        if ($race['race_start'] <= $currentDate) {
            $this->session->getFlashBag()->add('error', 'It is the day of the race, you cannot make or change predictions for this race anymore');

            return $this->redirectToRoute('/');
        }

        $userId = $this->getUser()->getId();

        if ($this->racePredictionsRepository->findOneBy(['race_id' => $raceId, 'user_id' => $userId]))
        {
            $this->racePredictionsRepository->removeUserRacePredictions(raceId: $raceId, userId: $userId);
        }
     
        $predictions = $this->request->get('driver_position');

        foreach ($predictions as $driverId => $prediction)
        {
            $this->racePredictionsRepository->savePrediction(raceId: $raceId, userId: $userId, driverId: $driverId, position: $prediction);
        }

        $this->session->getFlashBag()->add('success', "Your predictions of the {$race['name']} are saved");

        return $this->redirectToRoute("/$raceId");
    }
}