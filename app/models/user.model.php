<?php
declare(strict_types=1);

/**
 * User class for defining user model
 * @inheritDoc Database
 */
class User extends Database{

    private function __construct() {}
    public function get_all() {
        $db = self::connect();

        if ($db == null || $db == false) {
            return;
        }

        $query = "";
        $result = pg_query($db, $query);

        if (!$result) {
            throw new Exception("Query failed: " . pg_last_error());
        }

        return pg_fetch_all($result);
    }


    public function get($username) {
        $db = self::connect();

        if ($db == null || $db == false) {
            return;
        }

        $query = "";
        $result = pg_query($db, $query);

        if (!$result) {
            throw new Exception("Query failed: " . pg_last_error());
        }

        return pg_fetch_assoc($result);
    }


    public function set($username, $email, $password) {
        $db = self::connect();

        if ($db == null || $db == false) {
            return;
        }

        $query = "";
        $result = pg_query($db, $query);

        if (!$result) {
            throw new Exception("Query failed: " . pg_last_error());
        }
    }


    public function update_username($username, $new_username) {

    }


    public function update_email($username, $new_email) {

    }


    public static function update_password($username, $new_password) {

    }

    
    public static function delete($username) {

    }
    
}