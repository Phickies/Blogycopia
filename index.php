<?php

require("config.php");
require("app/controllers/Controller.php");
require("app/controllers/User.controller.php");
require("app/models/Database.php");
require("app/models/User.model.php");
require("app/models/Blog.model.php");

$contents = [
    "contents" => "This is the main page"
];


Controller::render("index", "Hello World", $contents);