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

        $this->addLink();
    }


    private function addLink() {
        $this->linkController(LoginController::class, "/", "GET", "renderLoginPage");
        $this->linkController(LoginController::class, "/", "POST", "authenticateUser");
    }
}