<?php

declare(strict_types=1);

namespace App\Authentication\Controllers;

use Core\Controller;


/**
 * RegisterController handles rendering the register page and user registeration.
 */
class RegisterController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function renderRegisterPage()
    {
        $this->render("Authentication/View/register.php", isCustomViewFile: true);
    }


    public function register()
    {
    }
}
