<?php

declare(strict_types=1);

namespace App\Error\Controllers;

use Core\Controller;


class ErrorController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function displayErrorPage(int $code, string $description)
    {

        $data = [
            "errorCode" => $code,
            "message" => $description,
        ];

        $this->render("Error/View/error.php", $data, true);
    }
}
