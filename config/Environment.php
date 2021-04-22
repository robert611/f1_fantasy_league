<?php 

namespace App\Config;

class Environment
{
    private $variables = [
        'env' => 'dev'
    ];

    public function getVariable(string $key): string
    {
        return $this->variables[$key];
    }
}