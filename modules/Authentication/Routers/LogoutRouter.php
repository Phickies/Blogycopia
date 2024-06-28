<?php

declare(strict_types=1);

namespace App\Authentication\Routers;

use App\Authentication\Controllers\LogoutController;
use Core\Router;


class LogoutRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->add("POST", "/", LogoutController::class, "logout");
    }
}