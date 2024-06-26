<?php

declare(strict_types=1);

use Helpers\Helper;


// Load .env file to extract data
Helper::loadEnv(BASE_DIR . "configs/.env");


// Declare config variable
$config = [

    "db" => [
        "host" => getenv("DB_HOST"),
        "port" => getenv("DB_PORT"),
        "name" => getenv("DB_NAME"),
        "user" => getenv("DB_USER"),
        "pass" => getenv("DB_PASS"),
    ],

];
