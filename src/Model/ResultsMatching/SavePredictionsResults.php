<?php 

namespace App\Model\ResultsMatching;

use App\Model\ResultsMatching\CompareResults;
use App\Model\Database\Repository\RacePredictionsResultsRepository;

class SavePredictionsResults
{
    private RacePredictionsResultsRepository $racePredictionsResultsRepository;

    public function __construct()
    {
        $this->racePredictionsResultsRepository = new RacePredictionsResultsRepository();
    }

    public function savePredictionsResultsToDatabase(array $usersRacePredictionsCollections, array $raceResults): void
    {
        $compareResults = new CompareResults($raceResults);

        $raceId = $raceResults[0]->getRace()->getId();

        foreach ($usersRacePredictionsCollections as $userId => $predictionsCollection)
        {
            $compareResults->comparePredictionsToResults($predictionsCollection);

            $gainedPoints = $compareResults->getGainedPoints();
            $matchedPositions = $compareResults->getMatchedPositions();

            $this->racePredictionsResultsRepository->savePredictionsResults(raceId: $raceId, userId: $userId, points: $gainedPoints);
        }
    }
}