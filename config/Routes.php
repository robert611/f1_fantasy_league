<?php 

$router->get('', 'IndexController', 'home');
$router->get('register', 'RegistrationController', 'showRegistrationForm');
$router->get('login', 'SecurityController', 'showLoginForm');