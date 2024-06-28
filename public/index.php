<?php

declare(strict_types=1);

define("BASE_DIR", dirname(__FILE__) . "/../");


require_once(BASE_DIR . "vendor/autoload.php");
require_once(BASE_DIR . "configs/config.php");

use App\Home\Router\HomeRouter;
use App\Authentication\Routers\AuthenticationRouter;

use Core\Router;
use Core\SessionHandler;

$router = new Router();
$session = new SessionHandler();

$router->addSession($session);

$router->addModule(HomeRouter::class);
$router->addModule(AuthenticationRouter::class, "/authentication");

$router->dispatchToModule();
