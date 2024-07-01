<?php

declare(strict_types=1);

namespace Core;

use PgSql\Connection;
use Core\Router;


class Model
{
    private ?Connection $connection;
    private array $config;

    /**
     * Model constructor.
     * Initializes the database connection and configuration.
     */
    public function __construct()
    {
        $this->connection = null;
        $this->config = require(BASE_DIR . "configs/config.php");
    }


    /**
     * Establishes a connection to the PostgreSQL database if not already connected.
     */
    public function connect()
    {
        if (!$this->connection) {
            $config = $this->config["db"];
            $host = $config["host"];
            $port = $config["port"];
            $dbname = $config["name"];
            $user = $config["user"];
            $password = $config["pass"];

            $connectionString = "host=$host port=$port dbname=$dbname user=$user password=$password";
            $this->connection = pg_connect($connectionString);
        }

        if (!$this->connection) {
            error_log("Connection error: " . pg_last_error());
            $router = new Router();
            $router->handleError(500, "Database connection failed");
        }
    }


    /**
     * Executes a query with the given parameters.
     *
     * @param string $sql SQL query to execute.
     * @param array $params Parameters for the SQL query.
     * @return \PgSql\Result|false Result resource on success, false on failure.
     */
    protected function query($sql, $params = [])
    {
        $this->connect();
        // Remove @ to unsuppress the warning and all errors.
        @$result = pg_query_params($this->connection, $sql, $params);
        return $result;
    }


    /**
     * Fetches all results from a query.
     *
     * @param string $sql SQL query to execute.
     * @param array $params Parameters for the SQL query.
     * @return array|false Array of results on success, false on failure.
     */
    protected function fetchAll($sql, $params = [])
    {
        $result = $this->query($sql, $params);

        if (!$result) {
            return false;
        }

        return pg_fetch_all($result);
    }


    /**
     * Fetches a single result from a query.
     *
     * @param string $sql SQL query to execute.
     * @param array $params Parameters for the SQL query.
     * @return array|false Associative array of the result on success, false on failure.
     */
    protected function fetch($sql, $params = [])
    {
        $result = $this->query($sql, $params);

        if (!$result) {
            return false;
        }

        return pg_fetch_assoc($result);
    }


    /**
     * Executes a query and returns the number of affected rows.
     *
     * @param string $sql SQL query to execute.
     * @param array $params Parameters for the SQL query.
     * @return int Number of affected rows.
     */
    protected function execute($sql, $params = [])
    {
        $result = $this->query($sql, $params);

        if (!$result) {
            return false;
        }

        return pg_affected_rows($result);
    }


    /**
     * Gets the last insert ID for a given sequence name.
     *
     * @param string $sequenceName Name of the sequence.
     * @return int|null Last insert ID or null if not found.
     */
    public function getLastInsertId($sequenceName)
    {
        $result = $this->fetch("SELECT currval($1)", [$sequenceName]);
        return $result ? $result['currval'] : null;
    }
}
