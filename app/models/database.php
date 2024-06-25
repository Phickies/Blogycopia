<?php
declare(strict_types=1);

/**
 * Database class for handling connection and data logic with DBMS
 */
class Database {

    private $connection = null;


    private function __construct() {
        $db_host = getenv("DB_HOST");
        $db_port = getenv("DB_PORT");
        $db_name = getenv("DB_NAME");
        $db_user = getenv("DB_USER");
        $db_pass = getenv("DB_PASS");
        
        self::$connection = pg_connect("
            host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass
        ");

        if (!self::$connection) {
            throw new Exception("Connection failed: " . pg_last_error());
            die();
        } else {
            echo "<script>console.log('Connected to the database successfully');</script>";
        }
    }

    
    /**
     * Establishing and returning connection with the DBMS
     */
    public function connect() {
        if (self::$connection === null) {
            new Database();
        }
        return self::$connection;
    }
}