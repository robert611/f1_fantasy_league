<?php 

namespace App\Model\RacePredictions;

use App\Model\Database\Repository\RacePredictionsResultsRepository;

class Ranking 
{
    private RacePredictionsResultsRepository $racePredictionsResultsRepository;

    public function __construct()
    {
        $this->racePredictionsResultsRepository = new RacePredictionsResultsRepository();
    }

    public function getUsersRanking(): array
    {
        $usersWithCombinedPoints = $this->racePredictionsResultsRepository->getUsersWithTheirCombinedPoints();

        if (false == $usersWithCombinedPoints) return [];

        $columns = array_column($usersWithCombinedPoints, 'points');

        array_multisort($columns, SORT_DESC, $usersWithCombinedPoints);
       
        return $usersWithCombinedPoints;
    }
}