<?php 

$router->get('', 'IndexController', 'home');
$router->get('register', 'RegistrationController', 'showRegistrationForm');
$router->post('register', 'RegistrationController', 'register');
$router->get('login', 'SecurityController', 'showLoginForm');
$router->post('login', 'SecurityController', 'login');
$router->get('logout', 'SecurityController', 'logout');