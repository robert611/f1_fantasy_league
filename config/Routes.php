<?php 

$router->get('/register', 'RegistrationController', 'showRegistrationForm');
$router->post('/register', 'RegistrationController', 'register');
$router->get('/login', 'SecurityController', 'showLoginForm');
$router->post('/login', 'SecurityController', 'login');
$router->get('/logout', 'SecurityController', 'logout');

$router->post('/api/registration/is/username/taken', 'RegistrationController', 'checkIfUsernameIsTaken');
$router->post('/api/registration/is/email/taken', 'RegistrationController', 'checkIfEmailIsTaken');

$router->get('/admin', 'AdminController', 'admin');

$router->get('/{race_id}', 'IndexController', 'home');