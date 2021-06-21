<?php 

namespace App\Test\Database;

use PHPUnit\Framework\TestCase;
use App\Model\RacePredictions\Ranking;
use App\Model\Database\Fixtures\RacePredictionsResultsFixtures;

final class RankingTest extends TestCase
{
    private Ranking $ranking;
    private RacePredictionsResultsFixtures $racePredictionsResultsFixtures;

    public function setUp(): void
    {
        $this->ranking = new Ranking();
        $this->racePredictionsResultsFixtures = new RacePredictionsResultsFixtures();
    }

    public function test_if_users_ranking_returns_empty_array_if_no_results()
    {
        $this->racePredictionsResultsFixtures->clear();

        $usersRanking = $this->ranking->getUsersRanking();

        $this->assertTrue(empty($usersRanking));

        $this->racePredictionsResultsFixtures->load();
    }

    public function test_if_users_ranking_returns_correct_data()
    {
        $usersRanking = $this->ranking->getUsersRanking();

        $this->assertEquals($usersRanking[0]['points'], 52);
        $this->assertEquals($usersRanking[1]['points'], 31);
        $this->assertEquals($usersRanking[2]['points'], 21);
        $this->assertTrue(is_string($usersRanking[0]['username']));
    }
}