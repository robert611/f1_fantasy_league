<?php 

namespace App\Model\Security\Voter;

use App\Model\Auth\User;
use App\Model\Security\Voter\RacePredictionsVoter;
use App\Model\Security\Voter\Exception\VoterNotFoundException;

class ManageVoters
{
    public function getVoters(): array
    {
        return [
            new RacePredictionsVoter()
        ];
    }

    public function isAccessAllowed(string $attribute, $subject, ?User $user): bool
    {
        $voters = $this->getVoters();

        foreach ($voters as $voter)
        {
            if ($voter->supports($attribute, $subject))
            {
                return $voter->voteOnAttribute($attribute, $subject, $user);
            }
        }

        throw new VoterNotFoundException('Voter could not be find.');
    }
}