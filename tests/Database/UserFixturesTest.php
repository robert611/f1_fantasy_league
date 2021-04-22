<?php 

namespace App\Test\Database;

use App\Model\Database\QueryBuilder;
use App\Model\Database\Fixtures\UserFixtures;
use PHPUnit\Framework\TestCase;

final class UserFixturesTest extends TestCase
{
    private $queryBuilder;
    private $userFixtures;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->userFixtures = new UserFixtures();
    }

    public function testClear()
    {
        $roleUser = '["ROLE_USER"]';

        $this->queryBuilder->executeQuery("INSERT INTO user VALUES (null, 'test_username', 'test@email.com', '$roleUser', 'password')");
        $this->queryBuilder->executeQuery("INSERT INTO user VALUES (null, 'test_username_two', 'test_two@email.com', '$roleUser', 'password')");
        
        $recordsCountBeforeClear = $this->queryBuilder->queryWithFetchAll("SELECT COUNT(*) as records_count FROM user");

        $this->userFixtures->clear();

        $recordsCountAfterClear = $this->queryBuilder->queryWithFetchAll("SELECT COUNT(*) as records_count FROM user");

        $this->userFixtures->load();

        $this->assertTrue($recordsCountBeforeClear[0]['records_count'] >= 2);
        $this->assertEquals($recordsCountAfterClear[0]['records_count'], 0);
    }

    public function testLoad()
    {
        $this->userFixtures->clear();

        $recordsCountBeforeLoad = $this->queryBuilder->queryWithFetchAll("SELECT COUNT(*) as records_count FROM user");

        $recordsToAddCount = count($this->userFixtures->getUserRecords());

        $this->userFixtures->load();

        $recordsCountAfterLoad = $this->queryBuilder->queryWithFetchAll("SELECT COUNT(*) as records_count FROM user");

        $this->assertEquals($recordsCountBeforeLoad[0]['records_count'] + $recordsToAddCount, $recordsCountAfterLoad[0]['records_count']);
    }
}