<?php 

session_start();

require 'vendor/autoload.php';

require './Router.php';
require './Request.php';

$router = new Router();

Router::load('./config/Routes.php')->direct(Request::uri(), Request::method());