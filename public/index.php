<?php

declare(strict_types=1);

define("BASE_DIR", dirname(__FILE__) . "/../");


require_once(BASE_DIR . "vendor/autoload.php");
require_once(BASE_DIR . "configs/config.php");

use App\Home\Controllers\HomeController;
use App\Login\Controllers\LoginController;
use Core\Router;


$router = new Router();


$router->add("GET", "/", HomeController::class, "displayPage");
$router->add("GET", "/login", LoginController::class, "displayPage");

$router->handleRequest();
