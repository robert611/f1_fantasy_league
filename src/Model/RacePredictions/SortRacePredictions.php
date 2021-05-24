<?php

namespace App\Model\RacePredictions;

class SortRacePredictions
{
    public static function sortByPosition(array $predictionsEntityCollection): array
    {
        usort($predictionsEntityCollection, function ($a, $b) {
            return $a->getPosition() - $b->getPosition();
        });

        return $predictionsEntityCollection;
    }
}