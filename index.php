<?php
declare(strict_types=1);

require("app/init.php");

$router = new Router();
$controller = new Controller();

$router->route("GET", "/", function() use ($controller) {
    $controller->render(name_view_file:"index", title:"Blogycopia", data:["heading" => "Welcome"]);
});

$router->route("GET", "/login", function() use ($controller) {
    $controller->render(name_view_file:"login", title:"Login");
});

$router->route("GET", "/register", function() use ($controller) {
    $controller->render(name_view_file:"register", title:"Register");
});

$router->route("GET", "/list", function($query) use ($controller) {
    $controller->render(name_view_file:"list", title:"List", data:$query);
});

$router->dispatch();