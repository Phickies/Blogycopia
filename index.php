<?php

require("controllers/app.php");

$nameViewFile = "index";

$contents = [
    "title" => "HelloWorlds",
    "contents" => "This is the main page"
];


render($nameViewFile, $contents);