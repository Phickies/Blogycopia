<?php

declare(strict_types=1);

define("BASE_DIR", dirname(__FILE__) . "/../");


require_once(BASE_DIR . "vendor/autoload.php");
require_once(BASE_DIR . "configs/config.php");

use App\Home\Router\HomeRouter;
use App\Login\Router\LoginRouter;
use App\Register\Router\RegisterRouter;
use Core\Router;


$router = new Router();

$router->addModule(HomeRouter::class);
$router->addModule(LoginRouter::class, "/login");
$router->addModule(RegisterRouter::class, "/register");

$router->dispatchToModule();
