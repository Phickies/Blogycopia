<?php

declare(strict_types=1);

namespace App\Authentication\Routers;

use App\Authentication\Controllers\RegisterController;
use Core\Router;


class RegisterRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->add("GET", "/", RegisterController::class, "renderRegisterPage");
    }
}