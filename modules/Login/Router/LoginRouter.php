<?php

declare(strict_types=1);

namespace App\Login\Router;

use App\Login\Controllers\LoginController;
use Core\Router;


class LoginRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->add("GET", "/", LoginController::class, "displayPage");
    }
}