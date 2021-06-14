<?php 

namespace App\Model\Security\Voter;

use App\Model\Auth\User;
use App\Model\Security\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Model\Database\Repository\RaceRepository;

class RacePredictionsVoter implements VoterInterface
{
    const STORE = 'STORE_RACE_PREDICTIONS';
    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }
    
    public function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::STORE]))
        {
            return false;
        }

        return true;
    }

    public function voteOnAttribute(string $attribute, $subject, ?User $user): bool
    {
        if (!$user instanceof User)
        {
            $this->session->getFlashBag()->add('error', 'You must be logged in to make this action');

            return false;
        }

        switch ($attribute) 
        {
            case self::STORE:
                return $this->canStore($subject, $user);
        }

        throw new \Exception('We could not confirm you permission to make this action');
    }

    public function canStore(null | int $raceId, User $user): bool
    {
        $raceRepository = new RaceRepository();

        if (!$race = $raceRepository->find((int) $raceId)) 
        {
            $this->session->getFlashBag()->add('error', 'You tried to predict results of the race that does not exist');

            return false;
        }

        $currentDate = date('Y-m-d');

        if ($race['race_start'] <= $currentDate)
        {
            $this->session->getFlashBag()->add('error', 'It is the day of the race or after, you cannot make or change predictions for this race anymore');

            return false;
        }

        return true;
    }
}