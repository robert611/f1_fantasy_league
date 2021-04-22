<?php 

namespace App\Test\Database;

use App\Model\Database\QueryBuilder;
use App\Model\Database\ManageDatabaseTables;
use App\Config\Database as DatabaseConfig;
use PHPUnit\Framework\TestCase;

final class ManageDatabaseTablesTest extends TestCase
{
    private $queryBuilder;
    private $manageDatabaseTables;

    public function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
        $this->manageDatabaseTables = new ManageDatabaseTables();
    }

    public function testDropTables()
    {
        $this->queryBuilder->executeQuery('CREATE TABLE test_names (name text)');
        $this->queryBuilder->executeQuery('CREATE TABLE test_colors (color text)');
        $this->queryBuilder->executeQuery('CREATE TABLE test_minions (minion text)');

        $this->manageDatabaseTables->dropTables(array_merge(['test_names', 'test_colors', 'test_minions'], $this->manageDatabaseTables->getTablesNames()));

        $databaseName = DatabaseConfig::getDatabaseConfiguration()['database_name'];

        $numberOfTablesAfterRemove = $this->queryBuilder->queryWithFetchAll("SELECT COUNT(*) as tables_count FROM information_schema.tables WHERE table_schema = '$databaseName'");

        $this->manageDatabaseTables->createTables();

        $this->assertEquals($numberOfTablesAfterRemove[0]['tables_count'], 0);
    }

    public function testCreateTables()
    {
        $this->manageDatabaseTables->dropTables($this->manageDatabaseTables->getTablesNames());

        $this->manageDatabaseTables->createTables();
 
        $databaseName = DatabaseConfig::getDatabaseConfiguration()['database_name'];

        $numberOfTablesAfterCreate = $this->queryBuilder->queryWithFetchAll("SELECT COUNT(*) as tables_count FROM information_schema.tables WHERE table_schema = '$databaseName'");

        $this->assertEquals(count($this->manageDatabaseTables->getQueriesCreatingTables()), $numberOfTablesAfterCreate[0]['tables_count']);
    }
}