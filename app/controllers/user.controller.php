<?php
declare(strict_types=1);

/**
 * This is the control logic
 */
class UserController {

    public function __construct(){}

    public function login() {

        if (!$_SERVER["REQUEST_METHOD"] == "POST") {
            return;
        }

        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        echo "$email and $password";
    }

    public function register() {
        echo "you registered";
    }

    public function delete() {
        echo "you delete ur accout?";
    }
}