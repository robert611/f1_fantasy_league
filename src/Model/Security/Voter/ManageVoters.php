<?php 

namespace App\Model\Security\Voter;

use App\Model\Auth\User;

class ManageVoters
{
    public function getVoters(): array
    {
        return [];
    }

    public function isAccessAllowed(string $attribute, $subject, User $user): bool
    {
        $voters = $this->getVoters();

        foreach ($voters as $voter)
        {
            if ($voter->supports($attribute, $subject))
            {
                return $voter->voteOnAttribute($attribute, $subject, $user);
            }
        }

        throw new \Exception('Voter could not be find');
    }
}