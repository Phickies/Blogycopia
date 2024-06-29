<?php

declare(strict_types=1);

namespace App\Authentication\Controllers;

use Core\Controller;


class AuthenController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function redirectToLoginPage()
    {
        $this->redirect("/authentication/login");
    }
}
