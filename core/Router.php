<?php

declare(strict_types=1);

namespace Core;


class Router
{

    private $routelist = [];


    public function __construct()
    {
    }


    public function add(string $method, string $uri, $controller, string $function)
    {
        $this->routelist[$method][$uri] = $function;
    }


    public function handleRequest()
    {
        $request = $this->getRequest();

        if (!$request) {
            echo "Bad request";
            http_response_code(400);
        }
    }


    /**
     * fetch and return the request method from the url.
     * null if not found or invalid
     */
    private function getMethod(): ?string
    {
        return $_SERVER["REQUEST_METHOD"];
    }


    /**
     * fetch and return the queries from the url.
     * null if not found or invalid
     */
    private function getQuery(): ?array
    {
        return $this->filterQuery($this->retrivedQuery());
    }


    /**
     * fetch and return the uri from the url.
     * null if not found or invalid
     */
    private function getUri(): ?string
    {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }


    /**
     * fetch and return method, uri and queries as a list from the url
     * null if invalid request
     */
    private function getRequest(): ?array
    {
        $method = $this->getMethod();
        $uri = $this->getUri();
        $params = $this->getQuery();

        if (!$method || !$uri || $params === null) {
            return null;
        }

        return [
            "method" => $method,
            "uri" => $uri,
            "query" => $params
        ];
    }


    private function filterQuery(array $query): ?array
    {

        if ($query === null) {
            return null;
        }

        foreach ($query as $key => &$value) {
            $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            if ($value == null) {
                return null;
            }
        }

        return $query;
    }


    private function retrivedQuery(): ?array
    {
        $queryString = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        $queryParams = [];
        // Parse the query from string to list
        if ($queryString) {
            parse_str($queryString, $queryParams);
        }
        return $queryParams;
    }
}
