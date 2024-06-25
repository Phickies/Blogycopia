<?php
declare(strict_types=1);

/**
 * Blog class for defining blog post object
 * @inheritDoc Database
 */
class Blog extends Database {

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


    public function get(string $search_string) {
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


    public function create(string $title, string $content): void{
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


    public function update_content(int $title_id, string $new_content) {

    }


    public function update_title(int $title_id, string $new_title) {

    }

    
    public function delete(int $id) {

    }
}