<?php

declare(strict_types=1);

namespace App\Home\Router;

use App\Home\Controllers\HomeController;
use Core\Router;


class HomeRouter extends Router
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->addLink();
    }


    private function addLink() {
        $this->linkController(HomeController::class, "/", "GET", "renderHomePage");
    }
}
