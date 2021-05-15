<?php

require __dir__ . './../../vendor/autoload.php';

use App\Model\Database\Fixtures\LoadFixtures;

$loadFixtures = new LoadFixtures;
$loadFixtures->clear();
$loadFixtures->load();

