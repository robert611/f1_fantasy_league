<?php 

namespace App\Model\Database\Fixtures;

interface FixturesInterface 
{
    public function load(): void;

    public function clear(): void;
}