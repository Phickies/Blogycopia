<?php

declare(strict_types=1);

namespace App\Authentication\Routers;

use Core\Router;
use App\Authentication\Routers\LoginRouter;
use App\Authentication\Routers\RegisterRouter;
use App\Authentication\Routers\LogoutRouter;


class AuthenticationRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->addModule(LoginRouter::class, "/login");
        $this->addModule(RegisterRouter::class, "/register");
        $this->addModule(LogoutRouter::class, "/logout");

        $this->add("GET", "/", AuthenticationRouter::class, "dispatchToModule");
    }
}