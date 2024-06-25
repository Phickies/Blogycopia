<?php
declare(strict_types=1);

/**
 * This is the control logic
 */
class UserController extends Controller {

    public function login() {

        if (!$_SERVER["REQUEST_METHOD"] == "POST") {
            return;
        }

        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        echo "$username, $password";
    }

    public function register() {
        echo "you registered";
    }

    public function delete() {
        echo "you delete ur accout?";
    }
}