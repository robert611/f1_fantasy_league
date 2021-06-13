<?php 

namespace App\Model\Security\Voter;

use App\Model\Auth\User;

interface VoterInterface
{
    public function supports(string $attribute, $subject): bool;

    public function voteOnAttribute(string $attribute, $subject, User $user): bool;
}