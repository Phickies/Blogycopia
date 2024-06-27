<?php

declare(strict_types=1);

namespace App\Home\Controllers;

use Core\Controller;


class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function displayPage($queryParams)
    {
        $data = array_merge($queryParams, [
            "heading" => "Welcome",
        ]);
        $this->render("Home/View/home.php", $data);
    }
}
