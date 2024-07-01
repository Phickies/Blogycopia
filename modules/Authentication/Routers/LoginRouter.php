<?php

declare(strict_types=1);

namespace App\Authentication\Routers;

use App\Authentication\Controllers\AuthenController;
use Core\Router;


class LoginRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();

        $this->addLink();
    }


    private function addLink() {
        $this->linkController(AuthenController::class, "/", "GET", "renderLoginPage");
        $this->linkController(AuthenController::class, "/", "POST", "authenticateUser");

        $this->linkController(AuthenController::class, "/forgot-password", "GET", "renderForgotPasswordPage");
        $this->linkController(AuthenController::class, "/forgot-password", "POST", "sendCodeToEmail");
    }
}