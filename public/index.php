<?php

declare(strict_types=1);

define("BASE_DIR", dirname(__FILE__) . "/../");


require_once(BASE_DIR . "vendor/autoload.php");
require_once(BASE_DIR . "configs/config.php");

use App\Home\Router\HomeRouter;
use App\Login\Router\LoginRouter;
use Core\Router;


$router = new Router();


$router->add("GET", "/", HomeRouter::class, "dispatch");
$router->add("GET", "/login", LoginRouter::class, "dispatch");

// $router->add(HomeRouter::class, "dispatch");
// $router->add(LoginRouter::class, "dispatch");

$router->dispatch();
