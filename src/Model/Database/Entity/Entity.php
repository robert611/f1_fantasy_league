<?php 

namespace App\Model\Database\Entity;

use App\Model\Database\QueryBuilder;

abstract class Entity
{    
    protected QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }
}