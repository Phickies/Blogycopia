<?php

/**
 * This is the control logic
 */
class UserController extends Controller {

    public static function login() {

        if (!is_valid_post_method()) {
            return;
        }

        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        echo "$username, $password";
    }

    public static function register() {
        echo "you registered";
    }

    public static function delete() {
        echo "you delete ur accout?";
    }
}