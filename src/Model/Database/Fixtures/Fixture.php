<?php 

namespace App\Model\Database\Fixtures;

use App\Model\Database\QueryBuilder;

class Fixture 
{
    protected $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }
}