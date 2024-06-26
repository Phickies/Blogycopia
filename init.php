<?php
declare(strict_types=1);

define("BASE_DIR", dirname(__FILE__));

require_once BASE_DIR . "/functions.php";

// Load environment variables
load_env(BASE_DIR . "/../.env");

// load application files
load_files([]);
