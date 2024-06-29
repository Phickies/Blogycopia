<?php

declare(strict_types=1);

define("BASE_DIR", dirname(__FILE__) . "/../");

require_once(BASE_DIR . "vendor/autoload.php");
require_once(BASE_DIR . "configs/config.php");

use Core\Router;
use Core\SessionHandler;

use App\Home\Router\HomeRouter;
use App\Authentication\Routers\AuthenticationRouter;


$router = new Router(new SessionHandler());

$router->linkRouter(HomeRouter::class, "/");
$router->linkRouter(AuthenticationRouter::class, "/authentication");

$router->dispatchToModule();
