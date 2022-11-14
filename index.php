<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use application\core\Router;
use application\core\Helper;

Helper::bindConfig('config', require_once 'config.php');

Router::load('application/routes.php')
    ->direct(Helper::uri(),Helper::method());