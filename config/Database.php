<?php 

namespace App\Config;

use App\Config\Environment;

class Database 
{
    public static function getDatabaseConfiguration(): array 
    {
        $env = (new Environment())->getVariable('env');
     
        return [
            'user' => 'root',
            'password' => '',
            'database_name' => $env == 'test' ? 'f1_fantasy_league_test' : 'f1_fantasy_league'
        ];
    }
}