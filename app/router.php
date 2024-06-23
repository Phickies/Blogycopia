<?php

define("PATH_DIR", dirname(__FILE__));

add_file("/configs/config.php");
add_file("/controllers/controller.php");
add_file("/controllers/user.controller.php");
add_file("/models/database.php");
add_file("/models/user.model.php");
add_file("/models/blog.model.php");

loadEnv(PATH_DIR . "/../.env");

function add_file($path){
    require(PATH_DIR . $path);
}
