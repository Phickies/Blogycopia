<?php

declare(strict_types=1);

require_once BASE_DIR . "/configs/.env";


$config = [

    "db" => [
        "host" => getenv("DB_HOST"),
        "port" => getenv("DB_PORT"),
        "name" => getenv("DB_NAME"),
        "user" => getenv("DB_USER"),
        "pass" => getenv("DB_PASS"),
    ],

];
