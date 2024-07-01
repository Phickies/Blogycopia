<?php

declare(strict_types=1);

namespace App\Authentication\Routers;

use App\Authentication\Controllers\AuthenController;
use Core\Router;


class LogoutRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();

        $this->addLink();
    }


    private function addLink() {
        $this->linkController(AuthenController::class, "/", "GET", "logout");
    }
}