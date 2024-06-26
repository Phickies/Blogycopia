<?php

declare(strict_types=1);

namespace Core;


class View {

    public function __construct() {}


    public function addHeader() {
        include_once(BASE_DIR . "/core/templates/header.php");
    }


    public function addFooter() {
        include_once(BASE_DIR . "/core/templates/footer.php");
    }
}