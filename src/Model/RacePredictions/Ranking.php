<?php 

namespace App\Model\RacePredictions;

use App\Model\Database\Repository\RacePredictionsResultsRepository;
use App\Model\Database\Repository\UserRepository;

class Ranking 
{
    private RacePredictionsResultsRepository $racePredictionsResultsRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->racePredictionsResultsRepository = new RacePredictionsResultsRepository();
        $this->userRepository = new UserRepository();
    }

    public function getUsersRanking(): array
    {
        $usersWithAllResults = $this->getUsersWithAllResults();
        $usersPoints = $this->countUsersAllPoints($usersWithAllResults);

        arsort($usersPoints);

        $ranking = $this->fillUsersData($usersPoints);
       
        return $ranking;
    }

    public function getUsersWithAllResults(): array
    {
        $usersIds = $this->racePredictionsResultsRepository->getUsersWithResultsIds();

        $usersResults = array();
        
        foreach ($usersIds as $userId)
        {
            $usersResults[$userId] = $this->racePredictionsResultsRepository->findBy(['user_id' => $userId]);
        }

        return $usersResults;
    }

    public function countUsersAllPoints(array $usersWithAllResults): array
    {
        $usersPoints = array();

        foreach ($usersWithAllResults as $userId => $userResults)
        {
            $userPoints = 0;

            foreach ($userResults as $result)
            {
                $userPoints += $result['points'];
            }

            $usersPoints[$userId] = $userPoints;
        }

        return $usersPoints;
    }

    public function fillUsersData(array $usersWithPoints): array
    {
        $ranking = array();

        foreach ($usersWithPoints as $userId => $points)
        {
            $ranking[] = ['user' => $this->userRepository->find($userId), 'points' => $points];
        }

        return $ranking;
    }
}