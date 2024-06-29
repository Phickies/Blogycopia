<?php

declare(strict_types=1);

namespace App\Authentication\Routers;

use Core\Router;
use App\Authentication\Routers\LoginRouter;
use App\Authentication\Routers\RegisterRouter;
use App\Authentication\Routers\LogoutRouter;

use App\Authentication\Controllers\AuthenController;


class AuthenticationRouter extends Router
{

    public function __construct()
    {
        parent::__construct();

        $this->addLink();
    }


    private function addLink()
    {
        $this->linkController(AuthenController::class, "/", "GET", "redirectToLoginPage");
        $this->linkController(AuthenController::class, "/hello", "GET", "redirectToLoginPage");
        
        $this->linkRouter(LoginRouter::class, "/login");
        $this->linkRouter(RegisterRouter::class, "/register");
        $this->linkRouter(LogoutRouter::class, "/logout");
    }
}
