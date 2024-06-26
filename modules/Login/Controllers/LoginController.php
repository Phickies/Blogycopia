<?php

declare(strict_types=1);

namespace App\Login\Controllers;

use Core\Controller;


class LoginController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function displayPage()
    {
        parent::render("");
    }
}
