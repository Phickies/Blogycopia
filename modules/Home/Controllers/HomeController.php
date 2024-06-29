<?php

declare(strict_types=1);

namespace App\Home\Controllers;

use Core\SessionHandler;
use Core\Controller;


class HomeController extends Controller
{

    private SessionHandler $session;
    
    public function __construct(SessionHandler $session)
    {
        parent::__construct();
        $this->session = $session;
    }


    public function renderHomePage($queryParams)
    {

        if(!$this->session->isAuthenticated()) {
            $this->redirect("/authentication/login");
        }

        $data = array_merge($queryParams, [
            
            "heading" => "Welcome",
            "username" => $_SESSION["username"],
        ]);

        $this->render("Home/View/home.php", $data, isCustomViewFile:true);
    }
}
