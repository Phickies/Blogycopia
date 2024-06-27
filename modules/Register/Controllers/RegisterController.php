<?php

declare(strict_types=1);

namespace App\Register\Controllers;

use Core\Controller;


class RegisterController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function displayPage()
    {
        $this->render("Register/View/register.php");
    }
}
