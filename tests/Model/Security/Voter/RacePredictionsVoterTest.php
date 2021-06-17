<?php 

namespace App\Tests\Model\Security\Voter;

use PHPUnit\Framework\TestCase;
use App\Model\Security\Voter\RacePredictionsVoter;
use App\Model\Security\Voter\Exception\AttributeMethodNotFoundException;
use App\Model\Database\Repository\UserRepository;
use App\Model\Auth\User;
use App\Model\Database\QueryBuilder;

final class RacePredictionsVoterTest extends TestCase
{
    private QueryBuilder $queryBuilder;
    private UserRepository $userRepository;
    private RacePredictionsVoter $racePredictionsVoter;
    private array $race;
    private User $user;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->userRepository = new UserRepository();
        $this->racePredictionsVoter = new RacePredictionsVoter();
        $this->race = $this->queryBuilder->queryWithFetch("SELECT * FROM race WHERE race_start > :date", ['date' => date('Y-m-d')]);
        $this->user = new User($this->userRepository->findAll()[0]);
    }

    public function test_if_supports_returns_correct_information()
    {
        $this->assertTrue($this->racePredictionsVoter->supports('STORE_RACE_PREDICTIONS', $this->race['id']));
        $this->assertFalse($this->racePredictionsVoter->supports('NOT_EXISTING_ATTRIBUTE', $this->race['id']));
    }

    /**
    * @runInSeparateProcess
    */
    public function test_if_vote_on_attribute_finds_attribute()
    {
        $isActionAllowed = $this->racePredictionsVoter->voteOnAttribute('STORE_RACE_PREDICTIONS', $this->race['id'], $this->user);

        $this->assertTrue($isActionAllowed);
    }

    public function test_if_vote_on_attribute_throws_exception_if_attribute_is_not_found()
    {
        $this->expectException(AttributeMethodNotFoundException::class);

        $this->racePredictionsVoter->voteOnAttribute('NOT_EXISTING_ATTRIBUTE',  $this->race['id'], $this->user);
    }

    /**
    * @runInSeparateProcess
    */
    public function test_if_user_must_be_logged_in()
    {
        $isActionAllowed = $this->racePredictionsVoter->voteOnAttribute('STORE_RACE_PREDICTIONS', $this->race['id'], null);

        $this->assertFalse($isActionAllowed);
    }

    /**
    * @runInSeparateProcess
    */
    public function test_if_race_must_exist_to_store_prediction()
    {
        $isActionAllowed = $this->racePredictionsVoter->voteOnAttribute('STORE_RACE_PREDICTIONS', -1, $this->user);

        $this->assertFalse($isActionAllowed);
    }

    /**
    * @runInSeparateProcess
    */
    public function test_if_race_must_happen_at_least_another_day_to_store_prediction()
    {
        $race = $this->queryBuilder->queryWithFetch("SELECT * FROM race WHERE race_start < :date", ['date' => date('Y-m-d')]);
        
        if ($race == false)
        {
            $this->assertTrue(true);
        }

        $isActionAllowed = $this->racePredictionsVoter->voteOnAttribute('STORE_RACE_PREDICTIONS', $race['id'], $this->user);        

        $this->assertFalse($isActionAllowed);
    }
}