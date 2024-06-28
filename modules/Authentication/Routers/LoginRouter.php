<?php

declare(strict_types=1);

namespace App\Authentication\Routers;

use App\Authentication\Controllers\LoginController;
use Core\Router;


class LoginRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->add("GET", "/", LoginController::class, "renderLoginPage");
        $this->add("POST", "/", LoginController::class, "authenticateUser");
    }
}